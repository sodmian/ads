<?php

namespace Core;

class Model
{
    private static ?\PDO $db = NULL;
    private static $instance = NULL;

    private function __construct(string $host, string $dbname)
    {
        try {
            self::$db = new \PDO("mysql:host=" . $host . ";dbname=" . $dbname, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET lc_time_names='ru_RU',NAMES utf8"]);
            self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Ошибка соединения: " . $e->getMessage();
        }
    }

    public static function getInstance(): ?\PDO
    {
        if (self::$instance == NULL) {
            $host = $_ENV['DB_HOST'];
            self::$instance = new self($host, $_ENV['DB_NAME']);
        }
        return self::$db;
    }

    public function __destruct()
    {
        self::$db = NULL;
        self::$instance = NULL;
    }
}