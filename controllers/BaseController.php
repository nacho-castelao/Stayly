<?php

class BaseController
{
    protected function requireAuth($redirect_url = '')
    {
        if (empty($_SESSION['user_id'])) {
            $isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

            if ($isAjaxRequest) {

                header('Content-Type: application/json');
                http_response_code(401);

                echo json_encode([
                    "status" => "unauthorized"
                ]);

                exit;
            }

            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'You must be logged in to use this feature!'
            ];

            header("Location: " . DEFAULT_URL . "public/Home/showLogin?redirect=$redirect_url");
            exit;
        }
    }

    protected function redirect($path)
    {
        header("Location: " . DEFAULT_URL . $path);
        exit;
    }

    protected function loadToast($type, $message)
    {
        $_SESSION['toast'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    protected function view($view, $data = [])
    {
        // Convert array keys into variables
        extract($data);

        // Load the view file
        require_once BASE_PATH . "/views/$view.php";
    }
}
