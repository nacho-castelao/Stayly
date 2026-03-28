<?php
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/controllers/BaseController.php';
class UserController extends BaseController
{

    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index() {}

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . DEFAULT_URL . "public/Home/showRegister");
            exit;
        }

        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $psw   = $_POST['psw'] ?? '';

        $registered = $this->userModel->register($name, $email, $psw);

        if ($registered) {

            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => 'Account created successfully.'
            ];

            header("Location: " . DEFAULT_URL . "public/Home/showLogin?success=1");
        } else {

            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Something went wrong. Please try again.'
            ];
            header("Location: " . DEFAULT_URL . "public/Home/showRegister?error=1");
        }

        exit;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            header("Location: " . DEFAULT_URL . "public/Home/showLogin");
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $psw = $_POST['psw'] ?? '';

        $user = $this->userModel->login($email, $psw);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user;

            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => 'Welcome back!'
            ];

            if (empty($_GET['redirect'])) {
                header("Location: " . DEFAULT_URL . 'public/Home/index');
                exit;
            }

            header("Location: " . DEFAULT_URL . "public/" . $_GET['redirect']);
            exit;
        } else {
            $_SESSION['login_failed'] = 1;
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Invalid credentials'
            ];

            header("Location: " . DEFAULT_URL . 'public/Home/showLogin');
            exit;
        }
    }

    public function logout()
    {
        session_start();

        $_SESSION = [];

        session_destroy();

        header("Location: " . DEFAULT_URL . "public/User/login");
        exit;
    }

    public function googleLogin()
    {
        $state = $_GET['redirect'] ?? '/';

        $client = new Google_Client();

        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);

        $client->addScope('email');
        $client->addScope('profile');

        $client->setState($state);

        $authUrl = $client->createAuthUrl();

        header('Location: ' . $authUrl);
        exit;
    }

    public function googleCallback()
    {
        session_start();

        if (isset($_GET['error'])) {
            header("Location: " . DEFAULT_URL . "Home/showRegister?login=false");
            exit;
        }

        if (!isset($_GET['code'])) {
            header("Location: " . DEFAULT_URL);
            exit;
        }

        $code = $_GET['code'];

        $client = new Google_Client();

        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);

        // Exchange code for token
        $token = $client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            header("Location: " . DEFAULT_URL);
            exit;
        }

        $client->setAccessToken($token);

        $oAuth = new Google_Service_Oauth2($client);
        $userInfo = $oAuth->userinfo->get();

        $user = new User();
        $existingUser = $user->findByGoogleId($userInfo->id);

        $redirect = $_GET['state'] ?? '';

        if (!$redirect) {
            $redirect = 'index.php';
        }

        if ($existingUser) {
            $_SESSION['user_id'] = $existingUser['id'];
            $_SESSION['user_name'] = $existingUser['name'];
            $_SESSION['user'] = $existingUser;
            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => 'Welcome back!'
            ];

            header("Location: " . DEFAULT_URL . "public/" . $redirect);
            exit;
        }

        // New user
        $newUserId = $user->createGoogleUser(
            $userInfo->id,
            $userInfo->name,
            $userInfo->email
        );

        $_SESSION['user_id'] = $newUserId;
        $_SESSION['user_name'] = $userInfo->name;
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Welcome!'
        ];

        header("Location: " . DEFAULT_URL . "public/" . $redirect);
        exit;
    }

    public function showDashboard()
    {
        $this->requireAuth();
        
        $this->view('user/dashboard',[
            'user' => $_SESSION['user']
        ]);
    }
}
