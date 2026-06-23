<?php

class Payment
{
    private int $id;
    private int $booking_id;
    private float $amount;
    private string $status = 'initiated';
    private ?string $provider = null;
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

    /**
     * Insert a payment row. Expects booking_id and amount to have been set;
     * status defaults to 'initiated' and provider may stay null until a real
     * payment provider is wired in. Columns are named explicitly so the insert
     * is not tied to the table's column order. Returns the new payment id.
     */
    public function create(): int
    {
        $sql = "
            INSERT INTO payments (booking_id, amount, status, provider)
            VALUES (:booking_id, :amount, :status, :provider)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'booking_id' => $this->booking_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'provider' => $this->provider,
        ]);

        $this->id = (int) $this->db->lastInsertId();

        return $this->id;
    }
}
?>
