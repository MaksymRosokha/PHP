<?php

declare(strict_types=1);

namespace Rosokha\DB\Factory;

use Rosokha\DB\Impl\TaskDAOImpl;
use Rosokha\DB\Impl\UserDAOImpl;

/**
 *
 */
interface DAOFactory
{
    /**
     * @return UserDAOImpl
     */
    public static function getUserDAO(): UserDAOImpl;

    /**
     * @return TaskDAOImpl
     */
    public static function getTaskDAO(): TaskDAOImpl;
}