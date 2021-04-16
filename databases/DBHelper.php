<?php

namespace Databases;

use Exception;
use PDO;

class DBHelper
{
    private static array $instances = [];

    protected PDO $connect;

    protected function __construct()
    {
        $this->connect = new PDO($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    }

    protected function __clone()
    {

    }

    /**
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot create new connection");
    }

    public static function getConnect(): DBHelper
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    public function DBConnect()
    {

    }


}