<?php

declare(strict_types=1);

namespace Rosokha\DB\entity;

/**
 *
 */
class Task
{
    /**
     * @var int
     */
    private int $id;
    /**
     * @var int
     */
    private int $idUser;
    /**
     * @var string
     */
    private string $content;
    /**
     * @var string|null
     */
    private string|null $dateOfWriting = null;
    /**
     * @var string|null
     */
    private string|null $dateOfEditing = null;
    /**
     * @var string
     */
    private string $status;

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
    public function getIdUser(): int
    {
        return $this->idUser;
    }

    /**
     * @param int $idUser
     */
    public function setIdUser(int $idUser): void
    {
        $this->idUser = $idUser;
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
     * @return string|null
     */
    public function getDateOfWriting(): string|null
    {
        return $this->dateOfWriting;
    }

    /**
     * @param string|null $dateOfWriting
     */
    public function setDateOfWriting(string|null $dateOfWriting): void
    {
        $this->dateOfWriting = $dateOfWriting;
    }

    /**
     * @return string|null
     */
    public function getDateOfEditing(): string|null
    {
        return $this->dateOfEditing;
    }

    /**
     * @param string|null $dateOfEditing
     */
    public function setDateOfEditing(string|null $dateOfEditing): void
    {
        $this->dateOfEditing = $dateOfEditing;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

}