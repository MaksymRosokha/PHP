<?php

final class DAOFactoryImpl implements DAOFactory {
    private static PDO $pdo;
    private static ?DAOFactoryImpl $instance = null;

    private function __construct(){
        $connectionManager = ConnectionManager::getInstance();
        self::$pdo = $connectionManager::get();
    }

    private function __clone() {}
    public function __wakeup() {}

    /**
     * Singleton pattern realisation
     * @return DAOFactoryImpl|null;
     */
    public static function getInstance(): ?DAOFactoryImpl {
        if(is_null(self::$instance)){
            self::$instance = new DAOFactoryImpl();
        }
        return self::$instance;
    }

    public static function getUserDAO(): UserDAO {
        return UserDAOImpl::getInstance(self::$pdo);
    }

    public static function getResponseDAO(): ResponseDAO {
        return ResponseDAOImpl::getInstance(self::$pdo);
    }
}
