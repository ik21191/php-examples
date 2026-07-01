<?php
class Database
{
    private static PDO|null $connection = null;

    public static function getConnection()
    {
        if (self::$connection === null) {
            try {
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                $charset = 'utf8mb4';
                $DSN = 'mysql:host=' . DB_HOST . ';dbname=' . DB_DATABASE_NAME . ';charset=' . $charset;
                self::$connection = new PDO($DSN, DB_USERNAME, DB_PASSWORD, $options);
            } catch (PDOException $e) {
                throw new Exception($e->getMessage());
            }
        }
        return self::$connection;
    }
}
