<?php

declare(strict_types=1);

namespace Rosokha\App\dto;

use Rosokha\DB\entity\User;

/**
 *
 */
class UserDTO
{
    /**
     * @var int
     */
    private int $id;
    /**
     * @var string
     */
    private string $login;
    /**
     * @var string
     */
    private string $password;
    /**
     * @var string
     */
    private string|null $avatar;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->login = $user->getLogin();
        $this->avatar = $user->getAvatar();
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
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): string|null
    {
        return $this->avatar;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $json = [
            'id' => $this->id,
            'logIn' => $this->login,
            'avatar' => $this->avatar,
        ];
        return json_encode($json);
    }

}