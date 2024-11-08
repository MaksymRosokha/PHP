<?php

use Entity\User;

/**
 * Interface for UserDAOImpl.
 * @author Rosokha Maksym
 */
interface UserDAO {
    function getById(int $key): ?User;
    function getByLogin(string $login): ?User;
    function getAll(): array;
    function save(User $entity): int;
    function update(User $entity): bool;
    function delete(int $key): bool;
}
