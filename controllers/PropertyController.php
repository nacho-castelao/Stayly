<?php
require_once '../models/Property.php';
require_once '../models/User.php';

class PropertyController{
    private $propertyModel;

    public function __construct(){
        $this->propertyModel = new Property();
    }
    
    public function index(){

    }
    
    public function showOne(){
        $id = $_GET['id'] ?? false;

        $this->propertyModel->setId($id);

        $prop = $this->propertyModel->getOne();
        $images = $this->propertyModel->getImages();
        $amenities = $this->propertyModel->getAmenities();
        $host = $this->propertyModel->getHostInfo();
        
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