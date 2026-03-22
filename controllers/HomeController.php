<?php 
require_once '../models/Property.php';

class HomeController{
    private $propertyModel;

    public function __construct()
    {
        $this->propertyModel = new Property();
    }

    public function index(){

        require_once BASE_PATH . "/views/layout/header.php";

        $properties = $this->propertyModel->getAll();
        
        require_once BASE_PATH . "/views/home/home.php";
        
        require_once BASE_PATH . "/views/layout/footer.php";
    }

    public function showRegister(){
        require_once '../views/home/register.php';
    }

    public function showLogin(){
        require_once '../views/home/login.php';
    }

    public function showHost(){
        require_once BASE_PATH . "/views/layout/header.php";

        require_once BASE_PATH . "/views/home/host.php";

        require_once BASE_PATH . "/views/layout/footer.php";
    }
}
?>