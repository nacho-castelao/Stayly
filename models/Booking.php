<?php

class Booking{
    private $id;
    private $property_id;
    private $user_id;
    private $start_date;
    private $end_date;
    private $created_in;
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



}
?>