<?php
    namespace Vendor;

    use PDO;
    use PDOException;

    class Connection{
        private static ?PDO $connection = null;

        public static function getConnection(): PDO
        {
            if (self::$connection === null) {
                $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
                $port = $_ENV['DB_PORT'] ?? '3306';
                $dbname = $_ENV['DB_NAME'] ?? '';
                $user = $_ENV['DB_USER'] ?? '';
                $pass = $_ENV['DB_PASS'] ?? '';

                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

                try {
                    self::$connection = new PDO($dsn, $user, $pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                } catch (PDOException $e) {
                    die("Error de conexión: " . $e->getMessage());
                }
            }

            return self::$connection;
        }
    }

?>