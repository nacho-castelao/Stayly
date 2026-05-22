<?php 

require_once BASE_PATH . "/controllers/BaseController.php";

class Wishlist extends BaseController
{
    public int $user_id;
    public int $property_id;
    public string $created_at;
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }
     
    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setUser_id(int $user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getProperty_id()
    {
        return $this->property_id;
    }

    public function setProperty_id(int $property_id)
    {
        $this->property_id = $property_id;

        return $this;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at(string $created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function save()
    {
        $sql = "INSERT INTO wishlist (user_id, property_id) VALUES (?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $this->user_id,
            $this->property_id
        ]);
    }

    public function delete()
    {
        $sql = "
            DELETE FROM wishlist 
            WHERE user_id = ? 
            AND property_id = ?
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $this->user_id,
            $this->property_id
        ]);
    }

    public function isSaved(){
        $sql = "
            SELECT * FROM wishlist
            WHERE user_id = ?
            AND property_id = ?
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $this->user_id,
            $this->property_id
        ]);

        $result = $stmt->fetchAll();
        $count = count($result);

        return $count > 0 ? true : false;
    }
}

?>