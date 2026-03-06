<?php

$clientID = $_ENV['GOOGLE_CLIENT_ID'];
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];

define("BASE_PATH", dirname(__DIR__));
define("DEFAULT_URL","http://localhost/PROYECTOAIRBNB/");
define("DEFAULT_CONTROLLER","HomeController");
define("DEFAULT_ACTION","index");
define("GOOGLE_CLIENT_ID",$clientID);
define("GOOGLE_CLIENT_SECRET",$clientSecret);
define("GOOGLE_REDIRECT_URI","http://localhost/PROYECTOAIRBNB/public/User/googleCallback");

?>