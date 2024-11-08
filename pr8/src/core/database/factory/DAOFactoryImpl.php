<?php

declare(strict_types=1);

namespace Rosokha\DB\Factory;

use PDO;
use Rosokha\DB\Impl\TaskDAOImpl;
use Rosokha\DB\Impl\UserDAOImpl;
use Rosokha\DB\Util\ConnectionDB;

/**
 *
 */
class DAOFactoryImpl implements DAOFactory
{
    /**
     * @var PDO
     */
    private static PDO $pdo;
    /**
     * @var DAOFactoryImpl|null
     */
    private static ?DAOFactoryImpl $instance = null;

    /**
     *
     */
    private function __construct()
    {
        $connectionManager = ConnectionDB::getInstance();
        self::$pdo = $connectionManager::get();
    }

    /**
     * @return DAOFactoryImpl|null
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new DAOFactoryImpl();
        }
        return self::$instance;
    }

    /**
     * @return UserDAOImpl
     */
    public static function getUserDAO(): UserDAOImpl
    {
        return UserDAOImpl::getInstance(self::$pdo);
    }

    /**
     * @return TaskDAOImpl
     */
    public static function getTaskDAO(): TaskDAOImpl
    {
        return TaskDAOImpl::getInstance(self::$pdo);
    }

    /**
     * @return void
     */
    public function __wakeup()
    {
    }

    /**
     * @return void
     */
    private function __clone()
    {
    }
}