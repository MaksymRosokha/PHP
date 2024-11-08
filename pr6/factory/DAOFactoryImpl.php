<?php

final class DAOFactoryImpl implements DAOFactory {
    private static PDO $pdo;
    private static ?DAOFactoryImpl $instance = null;

    private function __construct(){
        $sonnectionManager = ConnectionManager::getInstence();
        self::$pdo = $connectionManager::get();
    }

    private function __clone() {}
    private function __wakeup() {}

    /**
     * Singleton pattern realisation
     * @return null;
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

    //Сюди добавляємо інші ДАО об'єкти
}
