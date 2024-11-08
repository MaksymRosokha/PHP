<?php

use Entity\User;

final class UserDAOImpl implements UserDAO {

    private const GET_ALL_SQL = "
    SELECT id,
           user_name,
           email
           user_group
           ip
      FROM users
    ";

    private const GET_SQL = self::GET_ALL_SQL . "WHERE id = :id";

    private const SAVE_SQL = "
    INSERT INTO users(user_name, email, user_password, user_group, ip)
    VALUES (:userName, :email, :userPassword, :userGroup, :IP)";

    private const UPDATE_SQL = "
    UPDATE users
       SET user_name = :userName,
           email =:email,
           user_password = :userPassword,
           user_group = :userGroup,
           ip = :ip
     WHERE id = :id";

    private ?PDO $pdo;

    private static ?UserDAOImpl $instance = null;

    private function __construct(?PDO $pdo) {
        $this->$pdo = $pdo;
    }

    private function __clone() {}
    private function __wakeup() {}

    /**
      * Singleton pattern realisation
      * @param PDO/null $pdo pdo connection
      * @return UserDAOImpl/null
    */
     public static function getInstance(?PDO $pdo): ?UserDAOImpl {
        if(is_null(self::$instance)) {
            self::$instance = new UserDAOImpl($pdo);
        }
        return self::$instance;
    }

    /**
      * get entity from table by key
      * @param int $key
      * @return User/null
    */
    public static function get(int $key): ?Entity\User {
        $statement = $this->pdo->prepare(self::GET_SQL);
        $statement->bindParam(':id', $key, PDO::PARAM_INT);

        if($statement->execute()) {
            $row = $statement->fetch();
            return $this->buildUser($row);
        }
        return null;
    }

    /**
     * Select all data from current table
     * @return array of entities
     */
    public function getAll(): array {
        $statement = $this->pdo->prepare(self::GET_ALL_SQL);
        if($statement->execute()) {
            $result = [];
            while ($row = $statement->fetch()) {
                array_push($result, $this->buildUser($row));
            }
            return $result;
        }
        return [];
    }

    /**
     * insert one row in table bu entity
     * @param User $entity
     * @return string last insert id
     */
    public function save(Entity\User $entity): string {
        $data = [
            ':usre_name' => $entity->getUserName(),
            ':email' => $entity->getEmail(),
            ':user_group' => $entity->getUserName(),
            ':ip' => $entity->getEmail(),
        ];
        $statement = $this->pdo->prepare(self::SAVE_SQL);
        $statement->bindParam(':user_name', $publisher_id, PDO::PARAM_INT);
        $statement->execute(params: $data);
        return $this->pdo->lastInsertId();
    }

    /**
     * Update one row in table by entity
     * @param User $entity
     * @return bool check if undated succeed
     */
    public function update(Entity\User $entity): bool {
        $data = [
            ':id' => $entity->getId(),
            ':user_name' => $entity->getUserName(),
            ':email' => $entity->getEmail(),
            ':user_group' => $entity->getUserGroup(),
            ':ip' => $entity->getUserIP(),
        ];
        $statement = $this->pdo->prepare(self::UPDATE_SQL);
        $statement->execute(params: $data);
        $rowCount = $statement->rowCount();
        return $rowCount != '0';
    }

    /**
     * @param int $key
     * @return bool
     */
    public function delete(int $key): bool {
        $statement = $this->pdo->prepare(self::DELETE_SQL);
        $statement->bindParam(':id', $key, PDO::PARAM_INT);
        $statement->execute();
        $rowCount = $statement->rowCount();
        return $rowCount != '0';
    }

    private function buildUser(array $row): Entity\User {
        $user = new Entity\User();
        $user->setId(intval($row['id']));
        $user->setUserName(intval($row['user_name']));
        $user->setEmail(intval($row['email']));
        $user->setUserGroup(intval($row['user_group']));
        $user->setUserIP(intval($row['ip']));
        return $user;
    }
}
