<?php

declare(strict_types=1);

namespace Rosokha\App\core;

use PDO;
use Rosokha\DB\util\ConnectionDB;

/**
 *
 */
abstract class Model
{
    /**
     * @var PDO
     */
    private PDO $connection;

    /**
     *
     */
    public function __construct()
    {
        $this->connection = ConnectionDB::getInstance()::get();
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @param PDO $connection
     */
    public function setConnection(PDO $connection): void
    {
        $this->connection = $connection;
    }
}
