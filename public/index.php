<?php
session_start();

define("BASE_PATH", dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

require_once BASE_PATH . '/core/Autoloader.php'; 
require_once BASE_PATH . '/core/Config.php';

$action = $_GET['action'] ?? DEFAULT_ACTION;

function loadDefault(){
    $controller = DEFAULT_CONTROLLER;
    $action = DEFAULT_ACTION;

    $obj = new $controller();
    $obj->$action();
}

if(isset($_GET['controller']) && preg_match("/^[A-Za-z][A-Za-z0-9_]*$/",$_GET['controller'])){
    $controller = $_GET['controller'].'Controller';

    if(class_exists($controller) && method_exists($controller,$action)){
        $obj = new $controller();
        $obj->$action();

    }elseif(class_exists($controller) && !method_exists($controller,$action)){
        $obj = new $controller();
        $action = DEFAULT_ACTION;
        $obj->$action();
        
    }else{
        loadDefault();
    }
}else{
    loadDefault();
}

?>