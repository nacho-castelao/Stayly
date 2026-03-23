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
        $redirect_to = "Host/wizard";
        $this->requireAuth($redirect_to);

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
                if (empty($_POST['country']) || empty($_POST['city']) || empty($_POST['address']) || empty($_POST['postal'])) {
                    $this->loadToast('warning', 'You must complete the form!');

                    header("Location: " . DEFAULT_URL . "public/Host/wizard?step=2");
                    exit;
                }

                $country = $_POST['country'];
                $city = $_POST['city'];
                $address = $_POST['address'];
                $zipcode = $_POST['postal'];
                // 1. Build full address
                $fullAddress = $address . ', ' . $city . ', ' . $zipcode . ', ' . $country;

                // 2. Prepare Nominatim URL
                $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($fullAddress);

                // 3. Create context (IMPORTANT: Nominatim requires User-Agent)
                $options = [
                    "http" => [
                        "header" => "User-Agent: Stayly/1.0 (Nacho project)\r\n"
                    ]
                ];

                $context = stream_context_create($options);

                // 4. Call API
                $response = file_get_contents($url, false, $context);

                // 5. Decode JSON
                $data = json_decode($response, true);

                // 6. Handle result
                if (empty($data)) {
                    $this->loadToast("warning", "We couldn’t find that address. Do not write the floor number.");

                    header("Location: " . DEFAULT_URL . "public/Host/wizard?step=2");
                    exit;
                }

                // 7. Extract coordinates (first result)
                $lat = $data[0]['lat'];
                $lon = $data[0]['lon'];
                $displayName = $data[0]['display_name'];
                
                $_SESSION['property']['country'] = $country;
                $_SESSION['property']['city'] = $city;
                $_SESSION['property']['address'] = $address;
                $_SESSION['property']['postal'] = $zipcode;
                $_SESSION['property']['latitude'] = $lat;
                $_SESSION['property']['longitude'] = $lon;
                $_SESSION['property']['full_address'] = $displayName;
            } elseif ($step === 4) {
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

                if ($_FILES['photos']['error'][0] !== 0) {
                    $this->loadToast('warning', 'You must upload some images!');
                    header("Location: " . DEFAULT_URL . "public/Host/wizard?step=3");
                    exit;
                }

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

                $uploadedImages = [];

                $tempDir = BASE_PATH."/assets/img/temp/";

                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0777, true);
                }

                foreach ($_FILES['photos']['tmp_name'] as $index => $tmpPath) {

                    $type = $_FILES['photos']['type'][$index];
                    $ext = pathinfo($_FILES['photos']['name'][$index], PATHINFO_EXTENSION);

                    if (!in_array($type, $allowedTypes)) {
                        continue;
                    }

                    $fileName = uniqid() . '.' . $ext;

                    // temporary folder (before property_id exists)
                    $destination = BASE_PATH. "/assets/img/temp/" . $fileName;

                    move_uploaded_file($tmpPath, $destination);

                    // store ONLY the path
                    $uploadedImages[] = $destination;
                }

                $_SESSION['property']['images'] = $uploadedImages;
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
                $this->propertyModel->setLongitude($_SESSION['property']['longitude']);
                $this->propertyModel->setLatitude($_SESSION['property']['latitude']);
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
