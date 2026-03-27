<?php

class Property
{
    private $id;
    private $user_id;
    private $title;
    private $desc;
    private $price;
    private $city;
    private $address;
    private $rooms;
    private $latitude;
    private $longitude;
    private $bathrooms;
    private $guests;
    private $type;
    private $created_in;
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDesc()
    {
        return $this->desc;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getCreated_in()
    {
        return $this->created_in;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setUser_id($user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function setDesc($desc): void
    {
        $this->desc = $desc;
    }

    public function setPrice($price): void
    {
        $this->price = $price;
    }

    public function setCreated_in($created_in): void
    {
        $this->created_in = $created_in;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getRooms()
    {
        return $this->rooms;
    }

    public function setRooms($rooms)
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getBathrooms()
    {
        return $this->bathrooms;
    }

    public function getGuests()
    {
        return $this->guests;
    }

    public function setGuests($guests)
    {
        $this->guests = $guests;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }


    public function setBathrooms($bathrooms)
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    public function getPaginated($limit, $offset)
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

    public function countAll()
    {
        $sql = "
            SELECT COUNT(DISTINCT p.id) as total
            FROM properties p
            INNER JOIN property_images i ON i.property_id = p.id
            WHERE i.is_main = 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch()['total'];
    }

    public function getOne()
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

    public function getImages()
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

    public function getAmenities()
    {
        $id = $this->getId();

        $sql = "
            SELECT a.name,a.icon FROM amenities a
            INNER JOIN property_amenities pa ON pa.amenity_id = a.id
            WHERE pa.property_id = ?;
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt;
    }

    public function getHostInfo()
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

    public function insertOne()
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
            INSERT INTO properties VALUES (NULL,:host_id,:title,:description,:city,:address,:price,:max_guests,:rooms,:bathrooms,NULL,'published',CURRENT_TIMESTAMP(),:latitude,:longitude,NULL,:type)
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

    public function insertAmenities()
    {
        $property_id = $this->db->lastInsertId();
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
            INSERT INTO property_amenities (property_id, amenity_id) VALUES (:property_id,:amenity_id)
        ";

        $stmt = $this->db->prepare($sql2);

        foreach ($amenities_ids as $amenity_id) {
            $stmt->execute([
                'property_id' => $property_id,
                'amenity_id' => $amenity_id
            ]);
        }
    }

    public function insertImages()
    {
        $property_id = $this->getId();

        $baseDir = BASE_PATH . "/assets/img/properties/uploads/$property_id/";
        $publicPath = "img/properties/uploads/$property_id/";

        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        $sql = "
            INSERT INTO property_images VALUES (NULL,:prop_id,:img_url,NULL,:is_main)
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
