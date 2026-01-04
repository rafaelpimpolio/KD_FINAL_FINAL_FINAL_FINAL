<?php
class Database
{
    private static $dbName = 'kd_sportswear';
    private static $dbHost = 'localhost';
    private static $dbUsername = 'root';
    private static $dbPassword = '';
    private static $conn = null;

    public static function Connection()
    {
        if (self::$conn === null) {
            self::$conn = new PDO(
                "mysql:host=localhost;dbname=kd_sportswear;charset=utf8mb4",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }
        return self::$conn;
    }
}
