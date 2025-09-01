<?php
namespace App\Core;
use PDO;
class DB {
    private static ?PDO $pdo = null;
    public static function get(): PDO{
        if(self::$pdo === null){
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
            self::$pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        }
        return self::$pdo;
    }
}
