<?php

/**
 * A guest's review of a completed stay.
 *
 * One review per booking (enforced by the UNIQUE key on booking_id). Beyond the
 * booking and guest, each review denormalizes property_id and host_id so the
 * property page and host rating aggregate from this single indexed table without
 * joining back through bookings/properties on every read. Those two keys are
 * always derived server-side from the booking — never trusted from the client.
 *
 * Property-bag style mirrors Booking/Payment: set the fields, then call a query
 * method. `created_in` maps to the DB column `created_at` (project convention);
 * created_at/updated_at are managed by the column defaults, never written here.
 */
class Review
{
    /** Category sub-ratings, in display order. Drives validation + SQL. */
    public const CATEGORIES = [
        'cleanliness'   => 'Cleanliness',
        'accuracy'      => 'Accuracy',
        'communication' => 'Communication',
        'checkin'       => 'Check-in',
        'location'      => 'Location',
        'value'         => 'Value',
    ];

    /** Max length accepted for the optional written comment. */
    public const COMMENT_MAX = 1000;

    private int $id;
    private int $booking_id;
    private int $property_id;
    private int $user_id;
    private int $host_id;
    private int $rating;
    /** @var array<string,int> keyed by the CATEGORIES keys */
    private array $categories = [];
    private ?string $comment = null;
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setBooking_id(int $booking_id): void
    {
        $this->booking_id = $booking_id;
    }

    public function setProperty_id(int $property_id): void
    {
        $this->property_id = $property_id;
    }

    public function setUser_id(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setHost_id(int $host_id): void
    {
        $this->host_id = $host_id;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    /** @param array<string,int> $categories keyed by CATEGORIES keys */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function setComment(?string $comment): void
    {
        $comment = $comment !== null ? trim($comment) : null;
        $this->comment = ($comment === null || $comment === '') ? null : $comment;
    }

    /** True when $score is an integer 1..5 — the rule for every rating field. */
    public static function isValidScore($score): bool
    {
        return filter_var($score, FILTER_VALIDATE_INT) !== false
            && (int) $score >= 1 && (int) $score <= 5;
    }

    /**
     * Persist a new review. Expects booking_id, property_id, user_id, host_id,
     * rating and all six categories to have been set. Columns are named
     * explicitly (not tied to table column order); created_at/updated_at fall to
     * the column defaults. Returns the new review id.
     */
    public function create(): int
    {
        $sql = "
            INSERT INTO reviews
                (booking_id, property_id, user_id, host_id, rating,
                 cleanliness, accuracy, communication, checkin, location, value, comment)
            VALUES
                (:booking_id, :property_id, :user_id, :host_id, :rating,
                 :cleanliness, :accuracy, :communication, :checkin, :location, :value, :comment)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'booking_id'    => $this->booking_id,
            'property_id'   => $this->property_id,
            'user_id'       => $this->user_id,
            'host_id'       => $this->host_id,
            'rating'        => $this->rating,
            'cleanliness'   => $this->categories['cleanliness'],
            'accuracy'      => $this->categories['accuracy'],
            'communication' => $this->categories['communication'],
            'checkin'       => $this->categories['checkin'],
            'location'      => $this->categories['location'],
            'value'         => $this->categories['value'],
            'comment'       => $this->comment,
        ]);

        $this->id = (int) $this->db->lastInsertId();

        return $this->id;
    }

    /**
     * Update an existing review's ratings and comment. Guarded by user_id in the
     * WHERE clause so a guest can only ever edit their own review (defence in
     * depth on top of the controller's ownership check). Booking/property/host
     * links are immutable, so they are never touched here. Returns true when a
     * row was actually updated.
     */
    public function update(): bool
    {
        $sql = "
            UPDATE reviews SET
                rating = :rating,
                cleanliness = :cleanliness,
                accuracy = :accuracy,
                communication = :communication,
                checkin = :checkin,
                location = :location,
                value = :value,
                comment = :comment
            WHERE id = :id AND user_id = :user_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'rating'        => $this->rating,
            'cleanliness'   => $this->categories['cleanliness'],
            'accuracy'      => $this->categories['accuracy'],
            'communication' => $this->categories['communication'],
            'checkin'       => $this->categories['checkin'],
            'location'      => $this->categories['location'],
            'value'         => $this->categories['value'],
            'comment'       => $this->comment,
            'id'            => $this->id,
            'user_id'       => $this->user_id,
        ]);

        return $stmt->rowCount() > 0;
    }

    /** Fetch a single review row by id, or false. */
    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Everything needed to decide whether $userId may review booking $bookingId,
     * fetched in one query so the controller can apply the rules without a stack
     * of round-trips. Returns false when the booking does not exist or is not the
     * user's; otherwise an array carrying the derived property_id/host_id, the
     * checkout date, a paid flag, and the existing review id (NULL if none).
     *
     * `paid` reflects a settled payment (the booking lifecycle only reaches
     * 'confirmed' through a paid payment, but we check payments directly so the
     * "payment succeeded" rule is explicit and not inferred from status alone).
     */
    public function reviewableContext(int $bookingId, int $userId): array|false
    {
        $sql = "
            SELECT b.id            AS booking_id,
                   b.user_id       AS guest_id,
                   b.end_date      AS end_date,
                   b.status        AS status,
                   p.id            AS property_id,
                   p.host_id       AS host_id,
                   r.id            AS review_id,
                   EXISTS (
                       SELECT 1 FROM payments pay
                       WHERE pay.booking_id = b.id AND pay.status = 'paid'
                   )               AS paid
            FROM bookings b
            INNER JOIN properties p ON p.id = b.property_id
            LEFT JOIN reviews r ON r.booking_id = b.id
            WHERE b.id = :booking_id AND b.user_id = :user_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['booking_id' => $bookingId, 'user_id' => $userId]);

        return $stmt->fetch();
    }

    /**
     * Aggregate rating summary for a property: total count, overall average,
     * a per-category average map, and the 1..5 star distribution — all from one
     * indexed (property_id) scan, so the property page never N+1s over reviews.
     * Returns total = 0 with null averages when the property has no reviews.
     *
     * @return array{total:int, average:?float, categories:array<string,?float>, distribution:array<int,int>}
     */
    public function getPropertyStats(int $propertyId): array
    {
        $sql = "
            SELECT COUNT(*)                       AS total,
                   ROUND(AVG(rating), 2)          AS average,
                   ROUND(AVG(cleanliness), 2)     AS cleanliness,
                   ROUND(AVG(accuracy), 2)        AS accuracy,
                   ROUND(AVG(communication), 2)   AS communication,
                   ROUND(AVG(checkin), 2)         AS checkin,
                   ROUND(AVG(location), 2)        AS location,
                   ROUND(AVG(value), 2)           AS value,
                   SUM(rating = 5)                AS d5,
                   SUM(rating = 4)                AS d4,
                   SUM(rating = 3)                AS d3,
                   SUM(rating = 2)                AS d2,
                   SUM(rating = 1)                AS d1
            FROM reviews
            WHERE property_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$propertyId]);
        $row = $stmt->fetch();

        $total = (int) ($row['total'] ?? 0);

        $categories = [];
        foreach (array_keys(self::CATEGORIES) as $key) {
            $categories[$key] = $total > 0 ? (float) $row[$key] : null;
        }

        return [
            'total'        => $total,
            'average'      => $total > 0 ? (float) $row['average'] : null,
            'categories'   => $categories,
            'distribution' => [
                5 => (int) ($row['d5'] ?? 0),
                4 => (int) ($row['d4'] ?? 0),
                3 => (int) ($row['d3'] ?? 0),
                2 => (int) ($row['d2'] ?? 0),
                1 => (int) ($row['d1'] ?? 0),
            ],
        ];
    }

    /**
     * Individual reviews for a property, newest first, joined to the guest for
     * their name + avatar. Capped by $limit (default 50) so a runaway list never
     * blows up the page; pagination can layer on later via $offset.
     */
    public function getForProperty(int $propertyId, int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT r.rating, r.cleanliness, r.accuracy, r.communication,
                   r.checkin, r.location, r.value, r.comment, r.created_at,
                   u.name AS guest_name, u.avatar_url AS guest_avatar
            FROM reviews r
            INNER JOIN users u ON u.id = r.user_id
            WHERE r.property_id = :property_id
            ORDER BY r.created_at DESC, r.id DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('property_id', $propertyId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Host-level rating summary across every property the host owns: total
     * reviews and the overall average. One indexed (host_id) scan. Returns
     * total = 0 / average = null for a host with no reviews yet.
     *
     * @return array{total:int, average:?float}
     */
    public function getHostStats(int $hostId): array
    {
        $sql = "
            SELECT COUNT(*) AS total, ROUND(AVG(rating), 2) AS average
            FROM reviews
            WHERE host_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$hostId]);
        $row = $stmt->fetch();

        $total = (int) ($row['total'] ?? 0);

        return [
            'total'   => $total,
            'average' => $total > 0 ? (float) $row['average'] : null,
        ];
    }
}
?>
