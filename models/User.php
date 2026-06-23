<?php

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $psw;
    private string $created_in;
    private \PDO $db;
    private string $googleId;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPsw()
    {
        return $this->psw;
    }

    public function getCreated_in()
    {
        return $this->created_in;
    }

    public function getGoogleId()
    {
        return $this->googleId;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPsw(string $psw): void
    {
        $this->psw = $psw;
    }

    public function setCreated_in(string $created_in): void
    {
        $this->created_in = $created_in;
    }

    public function setGoogleId(string $googleId): void
    {
        $this->googleId = $googleId;
    }

    public function register(string $name, string $email, string $psw)
    {
        $hash = password_hash($psw, PASSWORD_BCRYPT);

        try {
            $sql = "INSERT INTO users (name, email, password_hash, created_at) 
                    VALUES (:name, :email, :hash, CURRENT_TIMESTAMP())";

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':hash', $hash, PDO::PARAM_STR);

            $ex = $stmt->execute();

            return $ex; // returns boolean (true/false)

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function createGoogleUser(string $googleId, string $name, string $email)
    {
        $sql = "
            INSERT INTO users 
            (google_id, name, email, role, created_at) 
            VALUES (?, ?, ?, 'guest', NOW())
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$googleId, $name, $email]);

        return $this->db->lastInsertId();
    }

    public function findByGoogleId(string $googleId)
    {
        $sql = "
            SELECT * 
            FROM users 
            WHERE google_id = ?
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$googleId]);

        return $stmt->fetch();
    }

    public function login(string $email, string $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user; // Login success
        }

        return false; // Login failed
    }

    public function getLanguages()
    {
        $id = $this->getId();

        $sql = "
            SELECT l.name FROM languages l
            INNER JOIN user_languages ul ON ul.language_id = l.id
            WHERE ul.user_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetchAll();
    }

    public function getBookingsCount(int $id)
    {
        $sql = "
            SELECT COUNT(*) AS total 
            FROM bookings
            WHERE user_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch()['total'];
    }

    public function getBookings(int $id)
    {
        // Join the property and its cover image so the dashboard can render a
        // full booking card. LEFT JOIN on the image keeps bookings whose
        // property has no main image flagged.
        $sql = "
            SELECT b.*,
                   p.title AS property_title,
                   p.city  AS property_city,
                   i.image_url AS property_image
            FROM bookings b
            INNER JOIN properties p ON p.id = b.property_id
            LEFT JOIN property_images i
                   ON i.property_id = p.id AND i.is_main = 1
            WHERE b.user_id = ?
            ORDER BY b.start_date DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetchAll();
    }

    public function getHostPropertiesCount(int $id)
    {
        $sql = "
            SELECT COUNT(DISTINCT id) AS total
            FROM properties
            WHERE host_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch()['total'];
    }

    public function getHostProperties(int $id)
    {
        $sql = "
            SELECT *
            FROM properties
            WHERE host_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetchAll();
    }

    public function getWishlistCount(int $id)
    {
        $sql = "
            SELECT COUNT(DISTINCT p.id) as total
            FROM properties p
            INNER JOIN wishlist wi ON wi.property_id = p.id
            WHERE wi.user_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch()['total'];
    }

    public function getWishlist(int $id)
    {
        // Explicit columns (not SELECT *) so properties.id/created_at aren't
        // clobbered by the wishlist join. LEFT JOIN the cover image so a
        // property with no main image still shows.
        $sql = "
            SELECT p.id,
                   p.title,
                   p.city,
                   p.price_per_night,
                   i.image_url AS image
            FROM properties p
            INNER JOIN wishlist wi ON wi.property_id = p.id
            LEFT JOIN property_images i
                   ON i.property_id = p.id AND i.is_main = 1
            WHERE wi.user_id = ?
            ORDER BY wi.created_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetchAll();
    }

    public function delete(int $id)
    {
        $id = (int)$id;
        
        $sql = "
            DELETE
            FROM users 
            WHERE id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $deleted = $stmt->execute([$id]);

        return $deleted ? $deleted : false;
    }
}
