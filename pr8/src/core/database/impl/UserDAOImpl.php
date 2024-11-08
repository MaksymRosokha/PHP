<?php

declare(strict_types=1);

namespace Rosokha\DB\Impl;

use PDO;
use Rosokha\DB\dao\UserDAO;
use Rosokha\DB\entity\User;

/**
 *
 */
class UserDAOImpl implements UserDAO
{
    /**
     *
     */
    private const GET_ALL_SQL = "
    SELECT * 
      FROM users";

    /**
     *
     */
    private const GET_BY_ID_SQL = self::GET_ALL_SQL . " 
     WHERE id = :id";

    /**
     *
     */
    private const GET_BY_LOGIN_SQL = self::GET_ALL_SQL . " 
     WHERE login = :login";

    /**
     *
     */
    private const INSERT_SQL = "
    INSERT INTO users(logIn, password, avatar)
    VALUES (:logIn, :password, :avatar)";

    /**
     *
     */
    private const UPDATE_SQL = "
    UPDATE users
       SET logIn = :logIn, 
           password = :password, 
           avatar = :avatar
     WHERE id = :id";

    /**
     *
     */
    private const DELETE_SQL = "
    DELETE FROM users
     WHERE id = :id";

    /**
     * @var UserDAOImpl|null
     */
    private static ?UserDAOImpl $instance = null;
    /**
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @param PDO|null $pdo
     */
    private function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param PDO|null $pdo
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
     * @return void
     */
    public function __wakeup()
    {
    }

    /**
     * @param int $keyValue
     * @return User|null
     */
    public function getById(int $keyValue): ?User
    {
        $statement = $this->pdo->prepare(self::GET_BY_ID_SQL);
        $statement->bindParam(':id', $keyValue, PDO::PARAM_INT);
        $statement->setFetchMode(PDO::FETCH_CLASS, get_class(new User()));

        if ($statement->execute()) {
            return $statement->fetch();
        }
        return null;
    }

    /**
     * @param string $login
     * @return User|null
     */
    public function getByLogin(string $login): ?User
    {
        $statement = $this->pdo->prepare(self::GET_BY_LOGIN_SQL);
        $statement->bindParam(':login', $login, PDO::PARAM_STR);
        $statement->setFetchMode(PDO::FETCH_CLASS, get_class(new User()));
        if ($statement->execute()) {
            $user = $statement->fetch();
            if (is_bool($user)) {
                return null;
            }
            return $user;
        }
        return null;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $statement = $this->pdo->prepare(self::GET_ALL_SQL);
        $statement->setFetchMode(PDO::FETCH_CLASS, get_class(new User()));

        if ($statement->execute()) {
            return $statement->fetchAll();
        }
        return [];
    }

    /**
     * @param User $entity
     * @return int
     */
    public function insert(User $entity): int
    {
        $data = [
            ':logIn' => $entity->getLogin(),
            ':password' => $entity->getPassword(),
            ':avatar' => $entity->getAvatar(),
        ];

        $statement = $this->pdo->prepare(self::INSERT_SQL);
        $statement->execute(params: $data);
        return intval($this->pdo->lastInsertId());
    }

    /**
     * @param User $entity
     * @return bool
     */
    public function update(User $entity): bool
    {
        $data = [
            ':logIn' => $entity->getLogin(),
            ':password' => $entity->getPassword(),
            ':avatar' => $entity->getAvatar(),
            ':id' => $entity->getId(),
        ];

        $statement = $this->pdo->prepare(self::UPDATE_SQL);
        $statement->execute(params: $data);
        $rowCount = $statement->rowCount();
        return $rowCount != '0';
    }

    /**
     * @param int $keyValue
     * @return bool
     */
    public function delete(int $keyValue): bool
    {
        $statement = $this->pdo->prepare(self::DELETE_SQL);
        $statement->bindParam(':id', $keyValue, PDO::PARAM_INT);
        $statement->execute();
        $rowCount = $statement->rowCount();
        return $rowCount != '0';
    }

    /**
     * @return void
     */
    private function __clone()
    {
    }
}
