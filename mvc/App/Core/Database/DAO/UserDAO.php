<?php

declare(strict_types=1);

namespace Arakviel\DB\DAO;

use Arakviel\DB\Entity\User;

/**
 * Interface for UserDAOImpl.
 * @author Vereshchagin Olexandr
 */
interface UserDAO
{
    public function findById(int $pKeyValue): ?User;

    public function findAll(): array;

    public function insert(User $entity): string;

    public function update(User $entity): bool;

    public function delete(int $pKeyValue): bool;
}
