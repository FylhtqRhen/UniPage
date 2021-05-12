<?php

namespace App;

use PDO;

class DBConnection
{
    private static $connect;

    private static $instance;

    private function __construct()
    {
        $bd = getenv('DB_CONNECTION');
        $dbName = getenv('DB_DATABASE');
        $name = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $host = getenv('DB_HOST');
        $opt = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        try {
            self::$connect = new PDO("$bd:host=$host;dbname=$dbName", $name, $password, $opt);
        } catch (\PDOException $e) {
            die('Подключение не удалось: ');
        }
    }

    public static function get(): PDO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$connect;
    }
}
