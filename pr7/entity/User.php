<?php

namespace Entity;

class User {
    private int $id;
    private string $userName;
    private string $email;
    private string|null $password = null;
    private string $userGroup;
    private string $userIP;

    /**
     * Get the value of id
     */
    public function getId() :int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id) :void
    {
        $this->id = $id;
    }

    /**
     * Get the value of userName
     */
    public function getUserName() :string
    {
        return $this->userName;
    }

    /**
     * Set the value of userName
     */
    public function setUserName(string $userName) :void
    {
        $this->userName = $userName;
    }

    /**
     * Get the value of password
     */
    public function getPassword() :?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword(?string $password) :void
    {
        if($password !== null){
            $this->password = $password;
        }
    }

    /**
     * Get the value of email
     */
    public function getEmail() :string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email) :void
    {
        $this->email = $email;
    }

    /**
     * Get the value of userGroup
     */
    public function getUserGroup() :string
    {
        return $this->userGroup;
    }

    /**
     * Set the value of userGroup
     */
    public function setUserGroup(string $userGroup) :void
    {
        $this->userGroup = $userGroup;
    }

    /**
     * Get the value of userIP
     */
    public function getUserIP() :string
    {
        return $this->userIP;
    }

    /**
     * Set the value of userIP
     */
    public function setUserIP(string $userIP) :void
    {
        $this->userIP = $userIP;
    }
}