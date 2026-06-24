<?php
require_once BASE_PATH . '/models/Property.php';
require_once BASE_PATH . '/models/DateRange.php';
require_once BASE_PATH . '/controllers/BaseController.php';
class HomeController extends BaseController
{
    private Property $propertyModel;

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

    public function showByInput()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $search = $data['search'] ?? '';
        $startDate = $data['start_date'] ?? '';
        $endDate = $data['end_date'] ?? '';

        // Only narrow by availability when the client sent a full, valid stay.
        // DateRange is the same authoritative validator the booking flow uses,
        // so a bad/partial range simply falls back to a plain text search.
        $startIso = null;
        $endIso = null;
        if (trim($startDate) !== '' || trim($endDate) !== '') {
            $range = DateRange::fromStrings($startDate, $endDate);
            if ($range->isValid()) {
                $startIso = $range->startIso();
                $endIso = $range->endIso();
            }
        }

        $result = $this->propertyModel->getBySearch($search, $startIso, $endIso);

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
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
