<?php

declare(strict_types=1);

namespace Arakviel\DB\Factory;

use Arakviel\DB\Impl\UserDAOImpl;

interface DAOFactory
{
    public static function getUserDAO(): UserDAOImpl;
    // сюди добавляємо інші ДАО обєкти
    // ...
}
