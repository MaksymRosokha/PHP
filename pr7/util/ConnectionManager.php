<?php

require_once 'ConfigUtil.php';

/**
 * The class connect the database and returns it to be used.
 * singleton pattern
 * @author Rosokha Maksym
 */
final class ConnectionManager {

    private static ?ConnectionManager $instance = null;

    /**
     * Stores the instance of PDO class
     * @var PDO
     */
    private static PDO $pdo;

    /**
     * The constructor of ConnectionManager class
     */
    private function __construct(){
        self::open();
    }

    private function __clone(){}
    public function __wakeup(){}

    /**
     * Singleton pattern realisation
     * @return ?ConnectionManager
     */
    public static function getInstance(): ?ConnectionManager {
        if(is_null(self::$instance)) {
            self::$instance = new ConnectionManager();
        }
        return self::$instance;
    }

    /**
     * Method returns the connection of database.
     */
    public static function get(): PDO {
        if(empty(self::$pdo)){
            self::open();
        }
        return self::$pdo;
    }

    /**
     * Method establishes the connection of database and stores the connection in private variable $pdo.
     */
    private static function open(): void {
        $config = ConfigUtil::get();
        $dsn = "{$config['type']}: host={$config['host']};dbname={$config['dbname']}";

        try {
            self::$pdo = new PDO(
                $dsn,
                $config['user'],
                $config['password'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            exit ("Error to connect to DataBase: Error returned:>>" . $error->getMessage());
        }
    }
}
