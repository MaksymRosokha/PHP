<?php

declare(strict_types=1);

namespace Rosokha\DB\Impl;

use PDO;
use Rosokha\DB\dao\TaskDAO;
use Rosokha\DB\entity\Task;

/**
 *
 */
class TaskDAOImpl implements TaskDAO
{

    /**
     *
     */
    private const GET_ALL_SQL = "
    SELECT *
      FROM tasks";

    /**
     *
     */
    private const GET_BY_ID_SQL = self::GET_ALL_SQL . " 
    WHERE id = :id";

    /**
     *
     */
    private const INSERT_SQL = "
    INSERT INTO tasks(id_user, content, date_of_writing, date_of_editing, status)
    VALUES (:idUser, :content, :dateOfWriting, :dateOfEditing, :status)";

    /**
     *
     */
    private const UPDATE_SQL = "
    UPDATE tasks
       SET id_user = :idUser,
           content = :content,
           date_of_writing = :dateOfWriting,
           date_of_editing = :dateOfEditing,
           status = :status
     WHERE id = :id";

    /**
     *
     */
    private const DELETE_SQL = "
    DELETE FROM tasks
          WHERE id = :id ;";
    /**
     * @var TaskDAOImpl|null
     */
    private static ?TaskDAOImpl $instance = null;
    /**
     * @var PDO|null
     */
    private PDO|null $pdo;

    /**
     * @param PDO|null $pdo
     */
    private function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param PDO|null $pdo
     * @return TaskDAOImpl|null
     */
    public static function getInstance(?PDO $pdo): ?TaskDAOImpl
    {
        if (is_null(self::$instance)) {
            self::$instance = new TaskDAOImpl($pdo);
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
     * @return Task|null
     */
    public function getById(int $keyValue): ?Task
    {
        $statement = $this->pdo->prepare(self::GET_BY_ID_SQL);
        $statement->bindParam(':id', $keyValue, PDO::PARAM_INT);
        $statement->setFetchMode(PDO::FETCH_CLASS, get_class(new Task()));
        if ($statement->execute()) {
            return $statement->fetch();
        }
        return null;
    }

    /**
     * @param int $keyValue
     * @param string $orderByAttribute
     * @return array
     */
    public function getByUserId(int $keyValue, string $orderByAttribute = "all"): array
    {
        $sql = "";
        if ($orderByAttribute === "todo" || $orderByAttribute === "in progress" || $orderByAttribute === "complete") {
            $sql = self::GET_ALL_SQL . " 
            WHERE id_user = :idUser
              AND status = '" . $orderByAttribute . "' ;";
        } else {
            $sql = self::GET_ALL_SQL . " 
            WHERE id_user = :idUser;";
        }

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':idUser', $keyValue, PDO::PARAM_INT);

        if ($statement->execute()) {
            $tasks = [];
            while ($row = $statement->fetch()) {
                $task = new Task();
                $task->setId($row['id']);
                $task->setIdUser($row['id_user']);
                $task->setContent($row['content']);
                $task->setDateOfWriting($row['date_of_writing']);
                $task->setDateOfEditing($row['date_of_editing']);
                $task->setStatus($row['status']);

                $tasks[] = $task;
            }
            return $tasks;
        }
        return [];
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $statement = $this->pdo->prepare(self::GET_ALL_SQL);
        $statement->setFetchMode(PDO::FETCH_CLASS, get_class(new Task()));
        if ($statement->execute()) {
            return $statement->fetchAll();
        }
        return [];
    }

    /**
     * @param Task $entity
     * @return int
     * @throws \Exception
     */
    public function insert(Task $entity): int
    {
        $data = [
            ':idUser' => $entity->getIdUser(),
            ':content' => $entity->getContent(),
            ':dateOfWriting' => $entity->getDateOfWriting(),
            ':dateOfEditing' => $entity->getDateOfEditing(),
            ':status' => $entity->getStatus(),
        ];

        $statement = $this->pdo->prepare(self::INSERT_SQL);

        if ($statement->execute($data)) {
            return intval($this->pdo->lastInsertId());
        }
        throw new \Exception();
    }

    /**
     * @param Task $entity
     * @return bool
     */
    public function update(Task $entity): bool
    {
        $data = [
            ':id_user' => $entity->getIdUser(),
            ':content' => $entity->getContent(),
            ':dateOfWriting' => $entity->getDateOfWriting(),
            ':dateOfEditing' => $entity->getDateOfEditing(),
            ':status' => $entity->getStatus(),
            ':id' => $entity->getId(),
        ];

        $statement = $this->pdo->prepare(self::UPDATE_SQL);
        $statement->execute(params: $data);
        return $statement->rowCount() != '0';
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
        return $statement->rowCount() != '0';
    }

    /**
     * @return void
     */
    private function __clone()
    {
    }
}