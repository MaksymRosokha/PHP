<?php

declare(strict_types=1);

namespace Rosokha\App\dto;

use Rosokha\DB\entity\Task;

/**
 *
 */
class TaskDTO
{
    /**
     * @var int
     */
    private int $id;
    /**
     * @var string
     */
    private string $content;
    /**
     * @var string|null
     */
    private string|null $dateOfWriting;
    /**
     * @var string|null
     */
    private string|null $dateOfEditing;
    /**
     * @var string
     */
    private string $status;

    /**
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->id = $task->getId();
        $this->content = $task->getContent();
        $this->dateOfWriting = $task->getDateOfWriting();
        $this->dateOfEditing = $task->getDateOfEditing();
        $this->status = $task->getStatus();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string|null
     */
    public function getDateOfWriting(): string|null
    {
        return $this->dateOfWriting;
    }

    /**
     * @return string|null
     */
    public function getDateOfEditing(): string|null
    {
        return $this->dateOfEditing;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $json = [
            'id' => $this->id,
            'content' => $this->content,
            'dateOfWriting' => $this->dateOfWriting,
            'dateOfEditing' => $this->dateOfEditing,
            'status' => $this->status,
        ];
        return json_encode($json);
    }
}