<?php

use Entity\User;

/**
 * DAO implementation of User entity
 */
final class UserDAOImpl implements UserDAO {

    private const GET_ALL_SQL = "
    SELECT *
      FROM users";

    private const GET_BY_ID_SQL = self::GET_ALL_SQL . "
     WHERE id = :id";

    private const GET_BY_LOGIN_SQL = self::GET_ALL_SQL . "
     WHERE user_name = :user_name";

    private const SAVE_SQL = "
    INSERT INTO users(user_name, email, user_password, user_group, ip)
    VALUES (:userName, :email, :userPassword, :userGroup, :ip)";

    private const UPDATE_SQL = "
    UPDATE users
       SET user_name = :userName,
           email =:email,
           user_password = :userPassword,
           user_group = :userGroup,
           ip = :ip
     WHERE id = :id";

    private const DELETE_SQL = "
    DELETE FROM users
          WHERE id = :id;";

    private ?PDO $pdo;

    private static ?UserDAOImpl $instance = null;

    /**
     * The constructor
     * @param PDO|null $pdo pdo connection
     */
    private function __construct(?PDO $pdo) {
        $this->pdo = $pdo;
    }

    private function __clone() {}
    public function __wakeup() {}

    /**
      * Singleton pattern realisation
      * @param PDO|null $pdo pdo connection
      * @return UserDAOImpl|null
    */
     public static function getInstance(?PDO $pdo): ?UserDAOImpl {
        if(is_null(self::$instance)) {
            self::$instance = new UserDAOImpl($pdo);
        }
        return self::$instance;
    }

    /**
      * getById entity from table by key
      * @param int $key
      * @return User|null
    */
    public function getById(int $key): ?Entity\User {
        $statement = $this->pdo->prepare(self::GET_BY_ID_SQL);
        $statement->bindParam(':id', $key, PDO::PARAM_INT);

        try {
            if ($statement->execute()) {
                $row = $statement->fetch();
                if(!empty($row)){
                    return $this->buildUser($row);
                } else {
                    return null;
                }
            }
            throw new PDOException();
        } catch (PDOException $e){
            throw new PDOException(message: "Не вдалося отримати користувача по даному id.", previous: $e);
        }
    }

    /**
     * getByLogin entity from table by login
     * @param login
     * @return User|null
     */
    function getByLogin(string $login): ?User {
        $statement = $this->pdo->prepare(self::GET_BY_LOGIN_SQL);
        $statement->bindParam(':user_name', $login);

        try {
            if ($statement->execute()) {
                $row = $statement->fetch();
                if(!empty($row)){
                    return $this->buildUser($row);
                } else {
                    return null;
                }
            }
            throw new PDOException();
        } catch (PDOException $e){
            throw new PDOException(message: "Не вдалося отримати користувача по даному login.", previous: $e);
        }
    }

    /**
     * Select all data from current table
     * @return array of entities
     * @throws Exception
     */
    public function getAll(): array {
        $statement = $this->pdo->prepare(self::GET_ALL_SQL);
        try {
            if ($statement->execute()) {
                $result = [];
                while ($row = $statement->fetch()) {
                    array_push($result, $this->buildUser($row));
                }
                return $result;
            }
            return [];
        } catch (PDOException $e){
            throw new PDOException(message: "Сталася помилка при отриманні користувачів.", previous: $e);
        }
    }

    /**
     * insert one row in table by entity
     * @param User $entity
     * @return int last insert id
     */
    public function save(Entity\User $entity): int {
        try {
            $data = [
                ':userName' => $entity->getUserName(),
                ':email' => $entity->getEmail(),
                ':userPassword' => $entity->getPassword(),
                ':userGroup' => $entity->getUserGroup(),
                ':ip' => $entity->getUserIP(),
            ];
            $statement = $this->pdo->prepare(self::SAVE_SQL);
            $statement->bindParam(':user_name', $publisher_id, PDO::PARAM_INT);
            $statement->execute(params: $data);
            return intval($this->pdo->lastInsertId());
        } catch(PDOException $e){
            throw new PDOException(message: "Сталася помилка при створенні нового користувача.", previous: $e);
        }
    }

    /**
     * Update one row in table by entity
     * @param User $entity
     * @return bool check if undated succeed
     */
    public function update(Entity\User $entity): bool {
        try {
            $data = [
                ':id' => $entity->getId(),
                ':userName' => $entity->getUserName(),
                ':email' => $entity->getEmail(),
                ':userPassword' => $entity->getPassword(),
                ':userGroup' => $entity->getUserGroup(),
                ':ip' => $entity->getUserIP(),
            ];
            $statement = $this->pdo->prepare(self::UPDATE_SQL);
            $statement->execute(params: $data);
            $rowCount = $statement->rowCount();
            return $rowCount != '0';
        } catch(PDOException $e){
            throw new PDOException(message: "Сталася помилка при оновленні даних користувача.", previous: $e);
        }
    }

    /**
     * Delete one row in table by id
     * @param int $key
     * @return bool
     */
    public function delete(int $key): bool {
        try {
            $statement = $this->pdo->prepare(self::DELETE_SQL);
            $statement->bindParam(':id', $key, PDO::PARAM_INT);
            $statement->execute();
            $rowCount = $statement->rowCount();
            return $rowCount != '0';
        } catch(PDOException $e){
            throw new PDOException(message: "Сталася помилка при видаленні користувача.", previous: $e);
        }
    }


    private function buildUser(array $row): Entity\User {
        try {
            $user = new Entity\User();
            $user->setId(id: intval($row['id']));
            $user->setUserName(userName: $row['user_name']);
            $user->setEmail(email: $row['email']);
            $user->setPassword(password: $row['user_password']);
            $user->setUserGroup(userGroup: $row['user_group']);
            $user->setUserIP(userIP: $row['ip']);
            return $user;
        } catch (Exception $exception){
            throw new Exception(message: "Сталася помилка при ініціалізації користувача.", previous: $exception);
        }
    }

}
