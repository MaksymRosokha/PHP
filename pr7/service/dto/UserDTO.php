<?php

declare(strict_types=1);

/**
 * The Data Transfer Object for User.
 */
class UserDTO {
    private int $id;
    private string $userName;
    private string $email;
    private string $userGroup;
    private string $userIP;

    public function __construct(\Entity\User $user) {
        $this->id = $user->getId();
        $this->userName = $user->getUserName();
        $this->email = $user->getEmail();
        $this->userGroup = $user->getUserGroup();
        $this->userIP = $user->getUserIP();
    }

    public function getJsonFormat(): string{
        $object = array(
            'id' => $this->id,
            'userName' => $this->userName,
            'email' => $this->email,
            'userGroup' => $this->userGroup,
            'userIP' => $this->userIP
        );
        return json_encode($object, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the value of id
     */
    public function getId() :int
    {
        return $this->id;
    }

    /**
     * Get the value of userName
     */
    public function getUserName() :string
    {
        return $this->userName;
    }

    /**
     * Get the value of email
     */
    public function getEmail() :string
    {
        return $this->email;
    }

    /**
     * Get the value of userGroup
     */
    public function getUserGroup() :string
    {
        return $this->userGroup;
    }

    /**
     * Get the value of userIP
     */
    public function getUserIP() :string
    {
        return $this->userIP;
    }
}