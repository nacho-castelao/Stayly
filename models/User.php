<?php

class User
{
    private $id;
    private $name;
    private $email;
    private $psw;
    private $created_in;
    private $db;
    private $googleId;

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

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function setPsw($psw): void
    {
        $this->psw = $psw;
    }

    public function setCreated_in($created_in): void
    {
        $this->created_in = $created_in;
    }

    public function setGoogleId($googleId): void
    {
        $this->googleId = $googleId;
    }

    public function register($name, $email, $psw)
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

    public function createGoogleUser($googleId, $name, $email)
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

    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

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

    public function findByGoogleId($googleId)
    {
        $sql = "
            SELECT * 
            FROM users 
            WHERE google_id = ?
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$googleId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
