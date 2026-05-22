<?php
require_once BASE_PATH . '/models/Property.php';
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/Wishlist.php';
class PropertyController{
    private Property $propertyModel;
    private Wishlist $wishlistModel;

    public function __construct(){
        $this->propertyModel = new Property();
        $this->wishlistModel = new Wishlist();
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
        
        $this->wishlistModel->setUser_id($_SESSION['user_id']);
        $this->wishlistModel->setProperty_id($id);
        $isSaved = $this->wishlistModel->isSaved();

        require_once BASE_PATH . "/views/layout/header.php";
        
        require_once BASE_PATH. '/views/property/show.php';

        require_once BASE_PATH . "/views/layout/footer.php";
    }
}
?>