<?php

declare(strict_types=1);

namespace Rosokha\DB\entity;

/**
 *
 */
class User
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
     * @var string|null
     */
    private string|null $avatar = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): string|null
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     */
    public function setAvatar(string|null $avatar): void
    {
        $this->avatar = $avatar;
    }

}