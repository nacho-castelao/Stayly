<?php

$clientID = $_ENV['GOOGLE_CLIENT_ID'] ?? null;
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? null;

define("DEFAULT_URL","http://localhost/Stayly/");
define("DEFAULT_CONTROLLER","HomeController");
define("DEFAULT_ACTION","index");
define("GOOGLE_CLIENT_ID",$clientID);
define("GOOGLE_CLIENT_SECRET",$clientSecret);
define("GOOGLE_REDIRECT_URI", DEFAULT_URL . "public/User/googleCallback");

?>