<?php

/**
 * DateRange — server-side validation/value object for a check-in/check-out stay.
 *
 * This is the authoritative backend mirror of the rules the datepicker
 * (assets/js/calendar.js) already enforces in the browser: dates arrive as the
 * hidden inputs `start_date` / `end_date` in `YYYY-MM-DD` form, the check-in may
 * not be in the past, and the check-out must be strictly after the check-in.
 * Client-side checks are UX only — the booking flow must re-validate here before
 * trusting anything.
 *
 * Pure and dependency-free (no DB, no session, no superglobals): construct it
 * from the raw request strings, then ask whether it is valid.
 *
 *   $range = DateRange::fromStrings($_POST['start_date'] ?? null, $_POST['end_date'] ?? null);
 *   if (!$range->isValid()) { // show $range->getErrors()
 *       ...
 *   }
 *   $nights = $range->nights();
 */
class DateRange
{
    // Guard against absurd ranges (e.g. a typo'd far-future check-out). Stays
    // longer than this are rejected rather than silently priced.
    private const MAX_NIGHTS = 365;

    private ?DateTimeImmutable $start;
    private ?DateTimeImmutable $end;
    private array $errors;

    private function __construct(?DateTimeImmutable $start, ?DateTimeImmutable $end, array $errors)
    {
        $this->start = $start;
        $this->end = $end;
        $this->errors = $errors;
    }

    /**
     * Build and validate a range from the two raw request strings.
     *
     * @param string|null        $start ISO date as sent by the datepicker (YYYY-MM-DD)
     * @param string|null        $end   ISO date as sent by the datepicker (YYYY-MM-DD)
     * @param DateTimeImmutable|null $today Injectable "today" (local midnight) for testing;
     *                                      defaults to the server's current day.
     */
    public static function fromStrings(?string $start, ?string $end, ?DateTimeImmutable $today = null): self
    {
        $today ??= new DateTimeImmutable('today');

        $errors = [];

        if ($start === null || trim($start) === '') {
            $errors['start_date'] = 'Please choose a check-in date.';
        }
        if ($end === null || trim($end) === '') {
            $errors['end_date'] = 'Please choose a check-out date.';
        }

        // Without both endpoints there is nothing further to check.
        if ($errors) {
            return new self(null, null, $errors);
        }

        $startDate = self::parseIsoDate($start);
        $endDate = self::parseIsoDate($end);

        if ($startDate === null) {
            $errors['start_date'] = 'The check-in date is not a valid date.';
        }
        if ($endDate === null) {
            $errors['end_date'] = 'The check-out date is not a valid date.';
        }

        if ($errors) {
            return new self(null, null, $errors);
        }

        // Range-level rules (both dates parsed successfully here).
        if ($startDate < $today) {
            $errors['start_date'] = 'The check-in date cannot be in the past.';
        }
        if ($endDate <= $startDate) {
            $errors['end_date'] = 'The check-out date must be after the check-in date.';
        } elseif ((int) $startDate->diff($endDate)->days > self::MAX_NIGHTS) {
            $errors['end_date'] = 'The stay cannot be longer than ' . self::MAX_NIGHTS . ' nights.';
        }

        if ($errors) {
            return new self(null, null, $errors);
        }

        return new self($startDate, $endDate, []);
    }

    public function isValid(): bool
    {
        return $this->errors === [];
    }

    /**
     * @return array<string,string> field name => human-readable message
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /** First validation message, or null when valid — handy for a toast. */
    public function firstError(): ?string
    {
        return $this->errors === [] ? null : reset($this->errors);
    }

    public function getStart(): ?DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): ?DateTimeImmutable
    {
        return $this->end;
    }

    /** Number of nights (check-out minus check-in). Zero when invalid. */
    public function nights(): int
    {
        if ($this->start === null || $this->end === null) {
            return 0;
        }
        return (int) $this->start->diff($this->end)->days;
    }

    /** Normalised check-in for storage/queries, or null when invalid. */
    public function startIso(): ?string
    {
        return $this->start?->format('Y-m-d');
    }

    /** Normalised check-out for storage/queries, or null when invalid. */
    public function endIso(): ?string
    {
        return $this->end?->format('Y-m-d');
    }

    /**
     * Strictly parse a YYYY-MM-DD date at local midnight. Returns null for any
     * malformed value or impossible calendar date (e.g. 2026-02-31), which
     * createFromFormat would otherwise roll over into March.
     */
    private static function parseIsoDate(string $value): ?DateTimeImmutable
    {
        $value = trim($value);

        // The leading "!" zeroes the time component so comparisons are date-only.
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if ($date === false) {
            return null;
        }

        $warnings = DateTimeImmutable::getLastErrors();
        if (is_array($warnings) && ($warnings['warning_count'] > 0 || $warnings['error_count'] > 0)) {
            return null;
        }

        // Round-trip guard: rejects values the parser silently normalised.
        if ($date->format('Y-m-d') !== $value) {
            return null;
        }

        return $date;
    }
}
