<?php

declare(strict_types=1);

namespace Arakviel\DB\Factory;

use Arakviel\DB\Impl\UserDAOImpl;
use Arakviel\DB\Util\ConnectionDB;
use PDO;

final class DAOFactoryImpl implements DAOFactory
{
    private static PDO $pdo;
    private static ?DAOFactoryImpl $instance = null;

    private function __construct()
    {
        // Create one global pdo connection
        $connectionManager = ConnectionDB::getInstance();
        self::$pdo = $connectionManager::get();
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    /**
     * Singleton pattern realisation
     * @return DAOFactoryImpl|null
     */
    public static function getInstance(): ?DAOFactoryImpl
    {
        if (is_null(self::$instance)) {
            self::$instance = new DAOFactoryImpl();
        }
        return self::$instance;
    }

    //=======================
    public static function getUserDAO(): UserDAOImpl
    {
        return UserDAOImpl::getInstance(self::$pdo);
    }
    // сюди добавляємо інші ДАО обєкти
    // ...
}
