<?php

namespace App;

use PDO;

class DBConnection
{
    /**
     * @var PDO
     */
    private static $connect;

    /**
     * @var DBConnection
     */
    private static $instance;

    /**
     * DBConnection constructor.
     */
    private function __construct()
    {
        $bd = getenv('DB_CONNECTION');
        $dbName = getenv('DB_DATABASE');
        $name = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $host = getenv('DB_HOST');
        $opt = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        self::$connect = new PDO("$bd:host=$host;dbname=$dbName", $name, $password, $opt);
    }

    /**
     * @return PDO
     */
    public static function get(): PDO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$connect;
    }
}
