<?php

namespace BrunoDuarte\DatabaseConnection;

use PDO;
use PDOException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Dotenv\Dotenv;

class ConnectionManager
{
    private static array $connections = [];
    private static Logger $logger;

    public static function initLogger(): void
    {
        self::$logger = new Logger('database');
        self::$logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/app.log', Logger::ERROR));
    }

    public static function loadEnv(string $path): void
    {
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();
    }

    public static function connect(string $alias): ?PDO
    {
        if (isset(self::$connections[$alias])) {
            return self::$connections[$alias];
        }

        try {
            $driver   = $_ENV[strtoupper($alias) . '_CONNECTION'];
            $host     = $_ENV[strtoupper($alias) . '_HOST'];
            $port     = $_ENV[strtoupper($alias) . '_PORT'];
            $dbname   = $_ENV[strtoupper($alias) . '_DATABASE'];
            $username = $_ENV[strtoupper($alias) . '_USERNAME'];
            $password = $_ENV[strtoupper($alias) . '_PASSWORD'];

            if ($driver === 'mysql') {
                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
            } elseif ($driver === 'pgsql') {
                $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            } else {
                throw new \InvalidArgumentException("Driver não suportado: $driver");
            }

            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

            self::$connections[$alias] = $pdo;
            return $pdo;
        } catch (\Throwable $e) {
            self::$logger->error("Erro de conexão [$alias]: " . $e->getMessage());
            return null;
        }
    }
}
