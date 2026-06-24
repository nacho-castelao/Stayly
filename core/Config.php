<?php

$clientID = $_ENV['GOOGLE_CLIENT_ID'] ?? null;
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? null;

define("DEFAULT_URL", $_ENV['APP_URL'] ?? "http://localhost/Stayly/");
define("DEFAULT_CONTROLLER","HomeController");
define("DEFAULT_ACTION","index");
define("GOOGLE_CLIENT_ID",$clientID);
define("GOOGLE_CLIENT_SECRET",$clientSecret);
define("GOOGLE_REDIRECT_URI", DEFAULT_URL . "public/User/googleCallback");

// Stripe (test keys in dev, live keys in prod) — all read from .env.
// STRIPE_WEBHOOK_SECRET is the signing secret printed by `stripe listen`
// locally, or shown when you create the webhook endpoint in the dashboard.
define("STRIPE_SECRET_KEY", $_ENV['STRIPE_SECRET_KEY'] ?? null);
define("STRIPE_PUBLISHABLE_KEY", $_ENV['STRIPE_PUBLISHABLE_KEY'] ?? null);
define("STRIPE_WEBHOOK_SECRET", $_ENV['STRIPE_WEBHOOK_SECRET'] ?? null);
define("PAYMENT_CURRENCY", $_ENV['PAYMENT_CURRENCY'] ?? 'eur');

?>