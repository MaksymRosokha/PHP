<?php

declare(strict_types=1);

namespace Arakviel\DB\Impl;

use Arakviel\DB\DAO\UserDAO;
use Arakviel\DB\Entity\User;
use PDO;

/**
 * DAO implementation of User Entity
 */
final class UserDAOImpl implements UserDAO
{
    // SQL convention = https://www.sqlstyle.guide/ru/
    private const GET_ALL_SQL = "
        SELECT id,
               first_name AS firstName,
               last_name AS lastName
          FROM users";

    private const GET_SQL = self::GET_ALL_SQL . "
        WHERE id = :id;";

    private const SAVE_SQL = "
        INSERT INTO users(first_name, last_name)
        VALUES (:first_name, :last_name);";

    private const UPDATE_SQL = "
        UPDATE users
           SET first_name = :first_name,
               last_name = :last_name
         WHERE id = :id;";

    private const DELETE_SQL = "
        DELETE FROM users
              WHERE id = :id;";

    /**
     * @var PDO|null - stores the instance of PDO class.
     */
    private PDO|null $pdo;

    /**
     * @var UserDAOImpl|null - singleton object
     */
    private static ?UserDAOImpl $instance = null;

    /**
     * @param PDO|null $pdo - pdo connection
     */
    private function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    /**
     * Singleton pattern realisation
     * @param PDO|null $pdo pdo connection
     * @return UserDAOImpl|null
     */
    public static function getInstance(?PDO $pdo): ?UserDAOImpl
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserDAOImpl($pdo);
        }
        return self::$instance;
    }

    /**
     * get User from table by pKeyValue
     * @param int $pKeyValue
     * @return User|null
     */
    public function findById(int $pKeyValue): ?User
    {
        $statement = $this->pdo->prepare(self::GET_SQL);
        $statement->bindParam(':id', $pKeyValue, PDO::PARAM_INT);
        $statement->setFetchMode(PDO::FETCH_CLASS, get_class(new User()));

        if ($statement->execute()) {
            return $statement->fetch();
        }
        return null;
    }

    /**
     * select all data from current table
     * @return array - of entities
     */
    public function findAll(): array
    {
        $statement = $this->pdo->prepare(self::GET_ALL_SQL);
        $statement->setFetchMode(PDO::FETCH_CLASS, get_class(new User()));

        if ($statement->execute()) {
            $result = [];
            while ($row = $statement->fetch()) {
                array_push($result, $row);
            }
            return $result;
        }
        return [];
    }

    /**
     * insert one row in table by User
     * @param User $User
     * @return string - last insert id
     */
    public function insert(User $User): string
    {
        $data = [
            ':first_name' => $User->getFirstName(),
            ':last_name' => $User->getLastName(),
        ];
        $statement = $this->pdo->prepare(self::SAVE_SQL);
        $statement->execute(params: $data);
        return $this->pdo->lastInsertId();
    }

    /**
     * update one row in table by User
     * @param User $User
     * @return bool check if updated succeed
     */
    public function update(User $User): bool
    {
        $data = [
            ':id' => $User->getId(),
            ':first_name' => $User->getFirstName(),
            ':last_name' => $User->getLastName(),
        ];
        $statement = $this->pdo->prepare(self::UPDATE_SQL);
        $statement->execute(params: $data);
        $rowCount = $statement->rowCount();
        return $rowCount != '0'; // is equal (return $rowCount != '0' ? true : false;)
    }

    /**
     * @param int $pKeyValue
     * @return bool
     */
    public function delete(int $pKeyValue): bool
    {
        $statement = $this->pdo->prepare(self::DELETE_SQL);
        $statement->bindParam(':id', $pKeyValue, PDO::PARAM_INT);
        $statement->execute();
        $rowCount = $statement->rowCount();
        return $rowCount != '0'; // is equal (return $rowCount != '0' ? true : false;)
    }
}
