<?php
require_once '../models/Property.php';
require_once '../models/User.php';

class PropertyController{

    public function index(){

    }
    
    public function showOne(){
        $id = $_GET['id'] ?? false;

        $property = new Property();
        $property->setId($id);

        $prop = $property->getOne();
        $images = $property->getImages();
        $amenities = $property->getAmenities();
        $host = $property->getHostInfo();
        
        $user = new User();
        $user->setId($prop['host_id']);
        $languages = $user->getLanguages();
        $languagesName = array_column($languages, 'name');
        $languagesPacked = implode(',',$languagesName);
        
        require_once BASE_PATH . "/views/layout/header.php";
        
        require_once BASE_PATH. '/views/property/show.php';

        require_once BASE_PATH . "/views/layout/footer.php";
    }
}
?>