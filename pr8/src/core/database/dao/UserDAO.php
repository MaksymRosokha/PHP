<?php

declare(strict_types=1);

namespace Rosokha\DB\dao;

use Rosokha\DB\entity\User;

/**
 *
 */
interface UserDAO
{
    /**
     * @param int $keyValue
     * @return User|null
     */
    public function getById(int $keyValue): ?User;

    /**
     * @param string $login
     * @return User|null
     */
    public function getByLogin(string $login): ?User;

    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param User $entity
     * @return int
     */
    public function insert(User $entity): int;

    /**
     * @param User $entity
     * @return bool
     */
    public function update(User $entity): bool;

    /**
     * @param int $keyValue
     * @return bool
     */
    public function delete(int $keyValue): bool;
}