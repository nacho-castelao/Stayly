<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
require_once '../core/Autoloader.php'; 
require_once '../core/Config.php';

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