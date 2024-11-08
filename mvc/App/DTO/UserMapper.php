<?php

declare(strict_types=1);

namespace Arakviel\App\DTO;

use Arakviel\DB\Entity\User;

final class UserMapper
{
    public static function toDTO(User $user): UserDTO
    {
        return new UserDTO(
            $user->getId(),
            $user->getFirstName(),
            $user->getLastName()
        );
    }

    public static function toEntity(UserDTO $userDTO): User
    {
        $user = new User();
        $user->setId($userDTO->getId());
        $user->setFirstName($userDTO->getFirstName());
        $user->setLastName($userDTO->getLastName());
        return $user;
    }
}
