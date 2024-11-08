<?php

declare(strict_types=1);

namespace Rosokha\DB\dao;

use Rosokha\DB\entity\Task;

/**
 *
 */
interface TaskDAO
{
    /**
     * @param int $keyValue
     * @return Task|null
     */
    public function getById(int $keyValue): ?Task;

    /**
     * @param int $keyValue
     * @return Task|null
     */
    public function getByUserId(int $keyValue, string $orderByAttribute = "all"): array;

    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param Task $entity
     * @return int
     */
    public function insert(Task $entity): int;

    /**
     * @param Task $entity
     * @return bool
     */
    public function update(Task $entity): bool;

    /**
     * @param int $keyValue
     * @return bool
     */
    public function delete(int $keyValue): bool;
}