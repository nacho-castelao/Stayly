<?php

class Database{
    private static $db;

    private function __construct(){}

    public static function connect(){
        if (!isset(self::$db)) {
            self::$db = new PDO(
                "mysql:host=localhost;dbname=stayly;charset=utf8mb4",
                "root",
                "",
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