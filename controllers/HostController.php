<?php
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/Property.php';
require_once BASE_PATH . '/controllers/BaseController.php';

class HostController extends BaseController
{
    private $userModel;
    private $propertyModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->propertyModel = new Property();
    }

    public function wizard()
    {
        $this->requireAuth();

        $step = (int) ($_GET['step'] ?? 1);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['step'])) {
            // save step data here...
            $step = (int)$_POST['step'];

            if ($step === 2) {
                if (empty($_POST['prop-type'])) {
                    $this->loadToast('warning', 'You must select the type of property to continue');

                    header("Location: " . DEFAULT_URL . "public/Host/wizard?step=1");
                    exit;
                }

                if (empty($_POST['title'])) {
                    $this->loadToast('warning', 'You must write a title for the property!');

                    header("Location: " . DEFAULT_URL . "public/Host/wizard?step=1");
                    exit;
                }

                $prop_type = $_POST['prop-type'];

                $_SESSION['property']['type'] = $prop_type;
                $_SESSION['property']['title'] = $_POST['title'];
            } elseif ($step === 3) {
                if (!isset($_POST['country']) || !isset($_POST['city']) || !isset($_POST['address']) || !isset($_POST['postal'])) {
                    $this->loadToast('warning', 'You must complete the form!');

                    header("Location: " . DEFAULT_URL . "public/Host/wizard?step=2");
                    exit;
                }

                $country = $_POST['country'];
                $city = $_POST['city'];
                $address = $_POST['address'];
                $zipcode = $_POST['postal'];

                $_SESSION['property']['country'] = $country;
                $_SESSION['property']['city'] = $city;
                $_SESSION['property']['address'] = $address;
                $_SESSION['property']['postal'] = $zipcode;
            } elseif ($step === 4) {

                if ($_FILES['photos']['error'][0] !== 0) {
                    $this->loadToast('warning', 'You must upload some images!');
                    header("Location: " . DEFAULT_URL . "public/Host/wizard?step=3");
                    exit;
                }

                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

                foreach ($_FILES['photos']['tmp_name'] as $index => $temp_path) {

                    $type = $_FILES['photos']['type'][$index];
                    $error = $_FILES['photos']['error'][$index];

                    // Check upload error
                    if ($error !== 0) {
                        $this->loadToast('warning', 'An error occurred. Check files!');
                        header("Location: " . DEFAULT_URL . "public/Host/wizard?step=3");
                        exit;
                    }

                    // Check type
                    if (!in_array($type, $allowedTypes)) {
                        $this->loadToast('warning', 'You must upload valid image files!');
                        header("Location: " . DEFAULT_URL . "public/Host/wizard?step=3");
                        exit;
                    }
                }

                $_SESSION['property']['images'] = $_FILES['photos'];
            } elseif ($step === 5) {
                if (empty($_POST['description']) || empty($_POST['amenities'])) {
                    $this->loadToast('warning', 'You must add a description and some amenities!');

                    header("Location: " . DEFAULT_URL . "public/Host/wizard?step=4");
                    exit;
                }

                $_SESSION['property']['description'] = $_POST['description'];
                $_SESSION['property']['amenities'] = $_POST['amenities'];
                $_SESSION['property']['guests'] = $_POST['max_guests'];
                $_SESSION['property']['rooms'] = $_POST['rooms'];
                $_SESSION['property']['bathrooms'] = $_POST['bathrooms'];
            } elseif ($step === 6) {
                if (!isset($_POST['price'])) {
                    $this->loadToast('warning', 'You must add a price');

                    header("Location: " . DEFAULT_URL . "public/Host/wizard?step=5");
                    exit;
                }

                $_SESSION['property']['price'] = $_POST['price'];
            } elseif ($step === 7) {
                $this->propertyModel->setUser_id($_SESSION['user_id']);
                $this->propertyModel->setTitle($_SESSION['property']['title']);
                $this->propertyModel->setDesc($_SESSION['property']['description']);
                $this->propertyModel->setPrice($_SESSION['property']['price']);
                $this->propertyModel->setAddress($_SESSION['property']['address']);
                $this->propertyModel->setRooms($_SESSION['property']['rooms']);
                $this->propertyModel->setBathrooms($_SESSION['property']['bathrooms']);
                $this->propertyModel->setType($_SESSION['property']['type']);
                $this->propertyModel->setCity($_SESSION['property']['city']);
                $this->propertyModel->setGuests($_SESSION['property']['guests']);

                $this->propertyModel->insertOne();
                $this->propertyModel->insertAmenities();
                $this->propertyModel->insertImages();
            }
        } elseif (isset($_GET['step'])) {
            $step = (int)$_GET['step'];
        }

        require_once BASE_PATH . '/views/property/wizard.php';
    }
}
