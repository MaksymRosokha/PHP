<?php

declare(strict_types=1);

namespace Entity;

class Response {
    /**
     * @var int
     */
    private int $id;
    /**
     * @var int
     */
    private int $userId;
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return ?string
     */
    public function getImageOrFile(): ?string
    {
        return $this->imageOrFile;
    }

    /**
     * @param string|null $imageOrFile
     */
    public function setImageOrFile(string|null $imageOrFile): void
    {
        $this->imageOrFile = $imageOrFile;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getDateOfWriting(): string
    {
        return $this->dateOfWriting;
    }

    /**
     * @param string $dateOfWriting
     */
    public function setDateOfWriting(string $dateOfWriting): void
    {
        $this->dateOfWriting = $dateOfWriting;
    }

}