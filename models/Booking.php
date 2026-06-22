<?php

class Booking{
    private int $id;
    private int $property_id;
    private int $user_id;
    private string $start_date;
    private string $end_date;
    private int $guests;
    private float $total_price;
    private string $status = 'pending';
    private string $created_in;
    private $db;

    public function __construct(){
        $this->db = Database::connect();
    }
    
    public function getId() {
        return $this->id;
    }

    public function getProperty_id() {
        return $this->property_id;
    }

    public function getUser_id() {
        return $this->user_id;
    }

    public function getStart_date() {
        return $this->start_date;
    }

    public function getEnd_date() {
        return $this->end_date;
    }

    public function getCreated_in() {
        return $this->created_in;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setProperty_id($property_id): void {
        $this->property_id = $property_id;
    }

    public function setUser_id($user_id): void {
        $this->user_id = $user_id;
    }

    public function setStart_date($start_date): void {
        $this->start_date = $start_date;
    }

    public function setEnd_date($end_date): void {
        $this->end_date = $end_date;
    }

    public function setCreated_in($created_in): void {
        $this->created_in = $created_in;
    }

    public function getGuests(): int {
        return $this->guests;
    }

    public function setGuests($guests): void {
        $this->guests = $guests;
    }

    public function getTotal_price(): float {
        return $this->total_price;
    }

    public function setTotal_price($total_price): void {
        $this->total_price = $total_price;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus($status): void {
        $this->status = $status;
    }

    /**
     * Persist a new booking. Expects user_id, property_id, start_date,
     * end_date, guests and total_price to have been set; status defaults to
     * 'pending' and created_at is filled by the column default. Columns are
     * named explicitly so the insert is not tied to the table's column order.
     * Returns the new booking id.
     */
    public function create(): int {
        $sql = "
            INSERT INTO bookings
                (user_id, property_id, start_date, end_date, guests, total_price, status)
            VALUES
                (:user_id, :property_id, :start_date, :end_date, :guests, :total_price, :status)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $this->user_id,
            'property_id' => $this->property_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'guests' => $this->guests,
            'total_price' => $this->total_price,
            'status' => $this->status,
        ]);

        $this->id = (int) $this->db->lastInsertId();

        return $this->id;
    }

    /**
     * True when an active (non-cancelled) booking for this property already
     * overlaps the requested range. Ranges are treated as half-open: a stay
     * ending on a day another stay starts does NOT count as an overlap, so
     * back-to-back bookings are allowed. Expects property_id, start_date and
     * end_date to have been set.
     *
     * Pass $lock = true (only meaningful inside a transaction) to take a
     * FOR UPDATE lock on the scanned range, so a concurrent request cannot
     * insert a conflicting booking between this check and our own insert.
     */
    public function hasOverlap(bool $lock = false): bool {
        $sql = "
            SELECT COUNT(*) AS total
            FROM bookings
            WHERE property_id = :property_id
              AND status <> 'cancelled'
              AND start_date < :end_date
              AND end_date > :start_date
        ";

        if ($lock) {
            $sql .= " FOR UPDATE";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'property_id' => $this->property_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        return (int) $stmt->fetch()['total'] > 0;
    }

    /**
     * Atomically check availability and insert. Runs the overlap check (with a
     * row lock) and the insert inside a single transaction, so two racing
     * requests cannot both pass the check and double-book the same dates.
     * Returns the new booking id, or 0 when the dates are already taken.
     */
    public function createIfAvailable(): int {
        $this->db->beginTransaction();
        try {
            if ($this->hasOverlap(true)) {
                // Nothing to write — commit just releases the locks we took.
                $this->db->commit();
                return 0;
            }

            $id = $this->create();
            $this->db->commit();

            return $id;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

}
?>