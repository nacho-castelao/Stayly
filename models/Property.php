<?php

class Property
{
    private ?int $id = null;
    private ?int $user_id = null;
    private ?string $title = null;
    private ?string $desc = null;
    private ?float $price = null;
    private ?string $city = null;
    private ?string $address = null;
    private ?int $rooms = null;
    private ?float $latitude = null;
    private ?float $longitude = null;
    private ?int $bathrooms = null;
    private ?int $guests = null;
    private ?string $type = null;
    private ?string $created_in = null;
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser_id(): ?int
    {
        return $this->user_id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDesc(): ?string
    {
        return $this->desc;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getCreated_in(): ?string
    {
        return $this->created_in;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUser_id(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDesc(string $desc): void
    {
        $this->desc = $desc;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setCreated_in(string $created_in): void
    {
        $this->created_in = $created_in;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getBathrooms(): ?int
    {
        return $this->bathrooms;
    }

    public function getGuests(): ?int
    {
        return $this->guests;
    }

    public function setGuests(int $guests): self
    {
        $this->guests = $guests;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setBathrooms(int $bathrooms): self
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    public function getPaginated(int $limit, int $offset): array
    {
        $offset = $offset < 0 ? 0 : $offset;

        $sql = "
            SELECT p.*, i.image_url AS url
            FROM properties p
            INNER JOIN property_images i ON i.property_id = p.id
            WHERE i.is_main = 1
            ORDER BY p.id DESC
            LIMIT :limit 
            OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getBySearch(string $search)
    {
        $search = '%'.$search.'%';

        $sql = "
            SELECT p.*, i.image_url AS url
            FROM properties p
            INNER JOIN property_images i ON i.property_id = p.id 
            WHERE i.is_main = 1
            AND p.city LIKE ?
            ORDER BY p.id DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$search]);

        return $stmt->fetchAll();
    }

    public function countAll(): int
    {
        $sql = "
            SELECT COUNT(DISTINCT p.id) as total
            FROM properties p
            INNER JOIN property_images i ON i.property_id = p.id
            WHERE i.is_main = 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return (int) $stmt->fetch()['total'];
    }

    public function getOne(): array|false
    {
        $id = $this->getId();

        $sql = "
            SELECT p.* FROM properties p
            WHERE id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getImages(): PDOStatement
    {
        $id = $this->getId();

        $sql = "
            SELECT * FROM property_images
            WHERE property_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt;
    }

    public function getAmenities(): PDOStatement
    {
        $id = $this->getId();

        $sql = "
            SELECT a.name,a.icon FROM amenities a
            INNER JOIN property_amenities pa ON pa.amenity_id = a.id
            WHERE pa.property_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt;
    }

    public function getHostInfo(): array|false
    {
        $id = $this->getId();

        $sql = "
            SELECT u.* FROM users u 
            INNER JOIN properties p ON p.host_id = u.id
            WHERE p.id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * True when the host has blocked any day within the requested stay.
     *
     * Availability is opt-out: a day is bookable unless an `availability` row
     * explicitly marks it `is_available = 0`. The absence of a row means open
     * (matching the column default), so newly created properties — which have
     * no availability rows — are fully bookable. The range is half-open: the
     * check-out day is not occupied, so it is not required to be open.
     *
     * This is only the host-block layer; existing reservations are checked
     * separately via Booking::hasOverlap().
     */
    public function isRangeBlocked(string $startDate, string $endDate): bool
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM availability
            WHERE property_id = :property_id
              AND is_available = 0
              AND date >= :start_date
              AND date < :end_date
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'property_id' => $this->getId(),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return (int) $stmt->fetch()['total'] > 0;
    }

    public function insertOne(): void
    {
        $host_id = $this->getUser_id();
        $title = $this->getTitle();
        $desc = $this->getDesc();
        $city = $this->getCity();
        $address = $this->getAddress();
        $price = $this->getPrice();
        $guests = $this->getGuests();
        $rooms = $this->getRooms();
        $bathrooms = $this->getBathrooms();
        $type = $this->getType();
        $lat = $this->getLatitude();
        $lon = $this->getLongitude();

        $sql = "
            INSERT INTO properties VALUES (
                NULL,:host_id,:title,:description,:city,:address,
                :price,:max_guests,:rooms,:bathrooms,NULL,
                'published',CURRENT_TIMESTAMP(),:latitude,
                :longitude,NULL,:type
            )
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'host_id' => $host_id,
            'title' => $title,
            'description' => $desc,
            'city' => $city,
            'address' => $address,
            'price' => $price,
            'max_guests' => $guests,
            'latitude' => $lat,
            'longitude' => $lon,
            'rooms' => $rooms,
            'bathrooms' => $bathrooms,
            'type' => $type
        ]);
    }

    public function insertAmenities(): void
    {
        $property_id = (int) $this->db->lastInsertId();
        $this->setId($property_id);

        $amenities = $_SESSION['property']['amenities'];

        if (empty($amenities)) {
            return;
        }

        $amenities_ids = [];

        $placeholders = implode(',', array_fill(0, count($amenities), '?'));

        $sql = "
            SELECT id FROM amenities
            WHERE name IN ($placeholders)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($amenities);

        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $amenities_ids[] = $row->id;
        }

        $sql2 = "
            INSERT INTO property_amenities 
            (property_id, amenity_id) 
            VALUES (:property_id,:amenity_id)
        ";

        $stmt = $this->db->prepare($sql2);

        foreach ($amenities_ids as $amenity_id) {
            $stmt->execute([
                'property_id' => $property_id,
                'amenity_id' => $amenity_id
            ]);
        }
    }

    public function insertImages(): void
    {
        $property_id = $this->getId();

        $baseDir = BASE_PATH . "/assets/img/properties/uploads/$property_id/";
        $publicPath = "img/properties/uploads/$property_id/";

        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        $sql = "
            INSERT INTO property_images 
            VALUES (NULL,:prop_id,:img_url,NULL,:is_main)
        ";

        $stmt = $this->db->prepare($sql);

        $images = $_SESSION['property']['images'];

        foreach ($images as $index => $tempPath) {

            $ext = pathinfo($tempPath, PATHINFO_EXTENSION);

            $finalPath = $baseDir . $index . '.' . $ext;

            rename($tempPath, $finalPath);

            $dbPath = $publicPath . $index . '.' . $ext;

            $stmt->execute([
                'prop_id' => $property_id,
                'img_url' => $dbPath,
                'is_main' => $index === 0 ? 1 : 0
            ]);
        }
    }
}
