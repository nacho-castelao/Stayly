<?php

class Payment
{
    private int $id;
    private int $booking_id;
    private float $amount;
    private string $status = 'initiated';
    private ?string $provider = null;
    private ?string $provider_ref = null;
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBooking_id(): int
    {
        return $this->booking_id;
    }

    public function setBooking_id(int $booking_id): void
    {
        $this->booking_id = $booking_id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(?string $provider): void
    {
        $this->provider = $provider;
    }

    public function getProvider_ref(): ?string
    {
        return $this->provider_ref;
    }

    public function setProvider_ref(?string $provider_ref): void
    {
        $this->provider_ref = $provider_ref;
    }

    /**
     * Insert a payment row. Expects booking_id and amount to have been set;
     * status defaults to 'initiated' and provider/provider_ref carry the
     * payment provider and its session/intent reference (e.g. a Stripe Checkout
     * Session id) used to correlate the later webhook back to this row. Columns
     * are named explicitly so the insert is not tied to the table's column
     * order. Returns the new payment id.
     */
    public function create(): int
    {
        $sql = "
            INSERT INTO payments (booking_id, amount, status, provider, provider_ref)
            VALUES (:booking_id, :amount, :status, :provider, :provider_ref)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'booking_id' => $this->booking_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'provider' => $this->provider,
            'provider_ref' => $this->provider_ref,
        ]);

        $this->id = (int) $this->db->lastInsertId();

        return $this->id;
    }

    /**
     * Fetch a single payment row by its provider reference (e.g. a Stripe
     * Checkout Session id), or false when none exists. Used by the payment
     * fulfillment path to map a provider event back to our booking.
     */
    public function findByProviderRef(string $providerRef): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM payments WHERE provider_ref = ? LIMIT 1"
        );
        $stmt->execute([$providerRef]);

        return $stmt->fetch();
    }

    /**
     * Mark the payment with the given provider reference as paid. Guarded by
     * the current status so a duplicate provider callback (webhook + redirect
     * both firing) is a no-op rather than a double settle. Returns true only
     * when this call performed the transition.
     */
    public function markPaid(string $providerRef): bool
    {
        $stmt = $this->db->prepare("
            UPDATE payments
            SET status = 'paid'
            WHERE provider_ref = :ref AND status <> 'paid'
        ");
        $stmt->execute(['ref' => $providerRef]);

        return $stmt->rowCount() > 0;
    }
}
?>
