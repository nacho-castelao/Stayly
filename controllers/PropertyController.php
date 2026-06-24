<?php
require_once BASE_PATH . '/models/Property.php';
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/Wishlist.php';
require_once BASE_PATH . '/models/Review.php';

class PropertyController{
    private Property $propertyModel;
    private Wishlist $wishlistModel;
    private Review $reviewModel;

    public function __construct(){
        $this->propertyModel = new Property();
        $this->wishlistModel = new Wishlist();
        $this->reviewModel = new Review();
    }
    
    public function index(){

    }

    // JSON feed of unavailable dates for the property's datepicker. Read-only;
    // the booking endpoint re-validates availability authoritatively.
    public function availability(){
        header('Content-Type: application/json');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            echo json_encode(['booked' => [], 'blocked' => []]);
            return;
        }

        $this->propertyModel->setId($id);

        echo json_encode($this->propertyModel->getUnavailability());
    }

    public function showOne(){
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $userId = $_SESSION['user_id'] ?? false;

        $this->propertyModel->setId($id ?: 0);
        $prop = $id ? $this->propertyModel->getOne() : false;

        // Unknown / non-numeric id: bounce home with a message instead of
        // fataling on $prop['host_id'] below.
        if (!$prop) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'That property could not be found.'];
            header('Location: ' . DEFAULT_URL . 'public/Home/index');
            exit;
        }

        $images = $this->propertyModel->getImages();
        $amenities = $this->propertyModel->getAmenities();
        $host = $this->propertyModel->getHostInfo();
        
        $user = new User();
        $user->setId($prop['host_id']);
        $languages = $user->getLanguages();
        $languagesName = array_column($languages, 'name');
        $languagesPacked = implode(',',$languagesName);
        
        if ($userId) {
            $this->wishlistModel->setUser_id($userId);
            $this->wishlistModel->setProperty_id($id);
            $isSaved = $this->wishlistModel->isSaved();
        }

        // Reviews: the property's rating summary + the individual reviews
        // (newest first), plus the host's overall rating for the host card.
        $reviewStats = $this->reviewModel->getPropertyStats((int) $prop['id']);
        $reviews = $reviewStats['total'] > 0
            ? $this->reviewModel->getForProperty((int) $prop['id'])
            : [];
        $hostStats = $this->reviewModel->getHostStats((int) $prop['host_id']);

        require_once BASE_PATH . "/views/layout/header.php";
        require_once BASE_PATH . '/views/property/show.php';
        require_once BASE_PATH . "/views/layout/footer.php";
    }
}
?>