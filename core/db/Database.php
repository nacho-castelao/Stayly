<?php

class Database{
    private static $db;

    private function __construct(){}

    public static function connect(){
        if (!isset(self::$db)) {
            // Connection params come from the environment (.env) so the app can
            // run against a real DB on deploy; fall back to the local XAMPP
            // defaults when they're unset (e.g. CLI checks without .env).
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $name = $_ENV['DB_NAME'] ?? 'stayly';
            $user = $_ENV['DB_USER'] ?? 'root';
            $pass = $_ENV['DB_PASS'] ?? '';

            self::$db = new PDO(
                "mysql:host=$host;dbname=$name;charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }

        return self::$db;
    }
}

?>