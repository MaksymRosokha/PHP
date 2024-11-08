<?php

declare(strict_types=1);

use Entity\Response;

final class ResponseDAOImpl implements ResponseDAO {
    
    private const GET_ALL_SQL = "
    SELECT *
      FROM responses";

    private const GET_BY_ID_SQL = self::GET_ALL_SQL . "
     WHERE id = :id";

    private const GET_NUMBER_SQL = "
    SELECT COUNT(id) AS number_of_responses
      FROM responses;";

    private const SAVE_SQL = "
    INSERT INTO responses(id_user, image_or_file, content, date_of_writing)
    VALUES (:userId, :imageOrFile, :content, :dateOfWriting)";

    private const UPDATE_SQL = "
    UPDATE responses
       SET id_user = :userId,
           image_or_file = :imageOrFile,
           content = :content,
           date_of_writing = :dateOfWriting
     WHERE id = :id";

    private const DELETE_SQL = "
    DELETE FROM responses
          WHERE id = :id;";

    private ?PDO $pdo;

    private static ?ResponseDAOImpl $instance = null;

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
     * @return ResponseDAOImpl|null
     */
    public static function getInstance(?PDO $pdo): ?ResponseDAOImpl {
        if(is_null(self::$instance)) {
            self::$instance = new ResponseDAOImpl($pdo);
        }
        return self::$instance;
    }

    /**
     * getById entity from table by key
     * @param int $key
     * @return Response|null
     */
    public function getById(int $key): ?Entity\Response {
        $statement = $this->pdo->prepare(self::GET_BY_ID_SQL);
        $statement->bindParam(':id', $key, PDO::PARAM_INT);

        try {
            if($statement->execute()) {
                $row = $statement->fetch();
                if(!empty($row)){
                    return $this->buildResponse($row);
                } else {
                    throw new PDOException();
                }
            }
            throw new PDOException();
        } catch (PDOException $exception){
            throw new PDOException(message: "Сталася помилка при отриманні відгуку по id.", previous: $exception);
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
                    array_push($result, $this->buildResponse($row));
                }
                return $result;
            }
            return [];
        } catch (PDOException $exception){
            throw new PDOException(message: "Сталася помилка при отриманні відгуків.", previous: $exception);
        }
    }

    function getNumber(): int {
        $statement = $this->pdo->prepare(self::GET_NUMBER_SQL);

        try {
            if($statement->execute()) {
                $row = $statement->fetch();
                if(!empty($row)){
                    return $row['number_of_responses'];
                } else {
                    throw new PDOException();
                }
            }
            throw new PDOException();
        } catch (PDOException $exception){
            throw new PDOException(message: "Сталася помилка при отриманні кількості відгуків.", previous: $exception);
        }
    }

    /**
     * insert one row in table by entity
     * @param Response $entity
     * @return int last insert id
     */
    public function save(Entity\Response $entity): int {
        try{
            $data = [
                ':userId' => $entity->getUserId(),
                ':imageOrFile' => $entity->getImageOrFile(),
                ':content' => $entity->getContent(),
                ':dateOfWriting' => $entity->getDateOfWriting(),
            ];
            $statement = $this->pdo->prepare(self::SAVE_SQL);
            $statement->execute(params: $data);
            return intval($this->pdo->lastInsertId());
        } catch (PDOException $exception){
            throw new PDOException(message: "Сталася помилка при створенні відгуку.", previous: $exception);
        }
    }

    /**
     * Update one row in table by entity
     * @param Response $entity
     * @return bool check if undated succeed
     */
    public function update(Entity\Response $entity): bool {
        try {
            $data = [
                ':id' => $entity->getId(),
                ':userId' => $entity->getUserId(),
                ':imageOrFile' => $entity->getImageOrFile(),
                ':content' => $entity->getContent(),
                ':dateOfWriting' => $entity->getDateOfWriting(),
            ];
            $statement = $this->pdo->prepare(self::UPDATE_SQL);
            $statement->execute(params: $data);
            $rowCount = $statement->rowCount();
            return $rowCount != '0';
        } catch (PDOException $exception){
            throw new PDOException(message: "Сталася помилка при оновленні даних відгуку.", previous: $exception);
        }
    }

    /**
     * Delete one row in table by id
     * @param int $key
     * @return bool
     */
    public function delete(int $key): bool {
        try{
            $statement = $this->pdo->prepare(self::DELETE_SQL);
            $statement->bindParam(':id', $key, PDO::PARAM_INT);
            $statement->execute();
            $rowCount = $statement->rowCount();
            return $rowCount != '0';
        } catch (PDOException $exception){
            throw new PDOException(message: "Сталася помилка при видаленні відгуку.", previous: $exception);
        }
    }

    public function getAllForAdminPanel(int $limit, int $offset,
        string $sortAttribute, string $sortByDescendingOrAscending) :array {

        $sql = "";

        if($sortAttribute == "user_name" ||
            $sortAttribute == "email" ||
            $sortAttribute == "date_of_writing") {
            if ($sortByDescendingOrAscending == "DESC" ||
                $sortByDescendingOrAscending == "ASC") {
                $sql = "SELECT users.user_name, users.email, responses.*
                          FROM responses
                    INNER JOIN users
                            ON users.id = responses.id_user
                         ORDER BY {$sortAttribute} {$sortByDescendingOrAscending}
                         LIMIT :limit
                        OFFSET :offset ;";
            }
        }

        try{
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
            $statement->bindParam(':offset', $offset, PDO::PARAM_INT);

            if ($statement->execute()) {
                $result = [];
                while ($row = $statement->fetch()) {
                    array_push($result, $this->buildResponse($row));
                }
                return $result;
            }
            return [];
        } catch (PDOException $exception){
            throw new PDOException(message: "Сталася помилка при створенні відгуків.", previous: $exception);
        }
    }

    private function buildResponse(array $row): Entity\Response {
        try {
            $response = new Entity\Response();
            $response->setId(id: intval($row['id']));
            $response->setUserId(userId: $row['id_user']);
            $response->setImageOrFile(imageOrFile: $row['image_or_file']);
            $response->setContent(content: $row['content']);
            $response->setDateOfWriting(dateOfWriting: $row['date_of_writing']);
            return $response;
        } catch (Exception $exception){
            throw new Exception(message: "Сталася помилка при ініціалізації відгука.", previous: $exception);
        }
    }
}