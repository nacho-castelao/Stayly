<?php
require_once '../models/Property.php';
require_once BASE_PATH . '/controllers/BaseController.php';
class HomeController extends BaseController
{
    private $propertyModel;

    public function __construct()
    {
        $this->propertyModel = new Property();
    }

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $page = (int) $page;
        $limit = 8;
        $offset = ($page - 1) * $limit;

        if ($page < 1) {
            $page = 1;
        }

        $this->view('layout/header');

        $properties = $this->propertyModel->getPaginated($limit, $offset);
        $total = $this->propertyModel->countAll();
        $totalPages = ceil($total / $limit);

        $this->view('home/home', [
            'properties' => $properties,
            'page' => $page,
            'totalPages' => $totalPages
        ]);

        $this->view('/layout/footer');
    }

    public function showRegister()
    {
        $this->view('home/register');
    }

    public function showLogin()
    {

        $redirect = $_GET['redirect'] ?? ' ';

        $this->view('home/login',[
            'redirect' => $redirect
        ]);
    }

    public function showHost()
    {
        require_once BASE_PATH . "/views/layout/header.php";

        require_once BASE_PATH . "/views/home/host.php";

        require_once BASE_PATH . "/views/layout/footer.php";
    }
}
