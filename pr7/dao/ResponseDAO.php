<?php

declare(strict_types=1);

use Entity\Response;

interface ResponseDAO {
    function getById(int $key): ?Response;
    function getAll(): array;
    function getNumber(): int;
    function save(Response $entity): int;
    function update(Response $entity): bool;
    function delete(int $key): bool;
    function getAllForAdminPanel(int $limit, int $offset,
                                string $sortAttribute, string $sortByDescendingOrAscending) :array;
}