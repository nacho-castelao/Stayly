<?php

class BaseController{
    protected function requireAuth($redirect_url){
        if (empty($_SESSION['user_id'])) {

            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'You must be logged in to use this feature!'
            ];

            header("Location: " . DEFAULT_URL . "public/Home/showLogin?redirect=$redirect_url");
            exit;
        }
    }

    protected function render($view, $data = []){
        extract($data);

        require dirname(__DIR__) . "/views/$view.php";
    }

    protected function redirect($path){
        header("Location: " . DEFAULT_URL . $path);
        exit;
    }

    protected function loadToast($type,$message) {
        $_SESSION['toast'] = [
            'type' => $type,
            'message' => $message
        ];
    }
}





















?>