<?php

declare(strict_types=1);

/**
 * The Data Transfer Object for Response.
 */
class ResponseDTO {
    /**
     * @var int
     */
    private int $id;
    /**
     * @var UserDTO
     */
    private UserDTO $author;
    /**
     * @var string|null
     */
    private string|null $imageOrFile;
    /**
     * @var string
     */
    private string $content;
    /**
     * @var string
     */
    private string $dateOfWriting;

    public function __construct(\Entity\Response $response)
    {
        $this->id = $response->getId();
        $this->setAuthor($response->getUserId());
        $this->imageOrFile = $response->getImageOrFile();
        $this->content = $response->getContent();
        $this->dateOfWriting = $response->getDateOfWriting();
    }

    public function getJsonFormat(): string{
        try {
            $object = [
                'id' => $this->id,
                'author' => json_decode($this->author->getJsonFormat(), true),
                'imageOrFile' => $this->imageOrFile,
                'content' => $this->content,
                'dateOfWriting' => $this->dateOfWriting
            ];
            return json_encode($object, JSON_UNESCAPED_UNICODE);
        } catch (Exception $exception){
            throw new Exception(message: "Не вдалося отримати автора", previous: $exception);
        }
    }

    private function setAuthor(int $id): void{
        $factoryDAO = DAOFactoryImpl::getInstance();
        $userDAO = $factoryDAO::getUserDAO();
        $this->author = new UserDTO($userDAO->getById($id));
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return UserDTO
     */
    public function getAuthor(): UserDTO
    {
        return $this->author;
    }

    /**
     * @return ?string
     */
    public function getImageOrFile(): ?string
    {
        return $this->imageOrFile;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getDateOfWriting(): string
    {
        return $this->dateOfWriting;
    }

}