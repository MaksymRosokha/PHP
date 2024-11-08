<?php

declare(strict_types=1);

namespace Arakviel\DB\Util;

use PDO;
use PDOException;

/**
 * The class connects the Database and returns it to be used.
 * singleton pattern
 * @author Vereshchagin Olexandr
 */
final class ConnectionDB
{

    /**
     * @var ConnectionDB|null - singleton object
     */
    private static ?ConnectionDB $instance = null;

    /**
     * @var PDO|null - stores the instance of PDO class.
     */
    private static ?PDO $pdo;

    private function __construct()
    {
        self::open();
    }

    public function __destruct()
    {
        self::$pdo = null;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    /**
     * Singleton pattern realisation
     * @return ConnectionDB|null
     */
    public static function getInstance(): ?ConnectionDB
    {
        if (is_null(self::$instance)) {
            self::$instance = new ConnectionDB();
        }
        return self::$instance;
    }

    /**
     * Method return the connection  of Database.
     */
    public static function get(): PDO
    {
        return self::$pdo;
    }

    /**
     * Method establishes the connection of Database and stores the connection in private variable $pdo.
     */
    private static function open(): void
    {
        $config = ConfigDB::importFromJSON();
        $dsn = "{$config['type']}:host={$config['host']};dbname={$config['dbname']}";

        try {
            self::$pdo = new PDO(
                $dsn,
                $config['user'],
                $config['password'],
                [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
            );
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            throw new PDOException('Error to connect to DataBase: Error returned:>> ' . $error->getMessage());
        }
    }
}
