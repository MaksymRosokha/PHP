<?php

declare(strict_types=1);

namespace Arakviel\App\Models;

use Arakviel\App\Core\Model;
use Arakviel\App\DTO\UserDTO;
use Arakviel\App\DTO\UserMapper;

class Main extends Model
{

    public function getUsers(): array
    {
        $userDTOs = [];
        $users = $this->db::getUserDAO()->findAll();
        foreach ($users as $user) {
            $userDTO = UserMapper::toDTO($user);
            $userDTOs[] = $userDTO;
        }
        return $userDTOs;
    }

    public function getUser(): UserDTO
    {
        $user = $this->db::getUserDAO()->findById(1);
        return UserMapper::toDTO($user);
    }
}
