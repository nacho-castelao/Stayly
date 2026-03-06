<?php 

class Property{
    private $id;
    private $user_id;
    private $title;
    private $desc;
    private $price;
    private $created_in;
    private $db;

    public function __construct(){
        $this->db = Database::connect();
    }

    public function getId() {
        return $this->id;
    }

    public function getUsuario_id() {
        return $this->user_id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDesc() {
        return $this->desc;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getCreated_in() {
        return $this->created_in;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setUser_id($user_id): void {
        $this->user_id = $user_id;
    }

    public function setTitle($title): void {
        $this->title = $title;
    }

    public function setDesc($desc): void {
        $this->desc = $desc;
    }

    public function setPrice($price): void {
        $this->price = $price;
    }

    public function setCreated_in($created_in): void {
        $this->created_in = $created_in;
    }

    public function getAll(){
        $sql = "
            SELECT p.*, i.image_url AS url
            FROM properties p
            INNER JOIN property_images i ON i.property_id = p.id
            WHERE i.is_main = 1
            ORDER BY p.id DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getOne(){
        $id = $this->getId();

        $sql ="
            SELECT p.* FROM properties p
            WHERE id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getImages(){
        $id = $this->getId();

        $sql = "
            SELECT * FROM property_images
            WHERE property_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt;
    }

    public function getAmenities(){
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

    public function getHostInfo(){
        $id = $this->getId();

        $sql ="
            SELECT u.* FROM users u 
            INNER JOIN properties p ON p.host_id = u.id
            WHERE p.id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

}
?>