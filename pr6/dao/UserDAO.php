<?php

use Entity\User;

/**
 * Interface for UserDAOImpl.
 * @author Rosokha Maksym
 */
interface UserDAO {
    function get(int $key): ?User;
    function getAll(): array;
    function save(User $entity): string;
    function update(User $entity): bool;
    function delete(int $key): bool;
}
