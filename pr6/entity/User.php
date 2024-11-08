<?php

namespace Entity;

class User {
    private int $id;
    private string $userName;
    private string $email;
    private string $userGroup;
    private string $userIP;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of userName
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set the value of userName
     *
     * @return  self
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of userGroup
     */
    public function getUserGroup()
    {
        return $this->userGroup;
    }

    /**
     * Set the value of userGroup
     *
     * @return  self
     */
    public function setUserGroup($userGroup)
    {
        $this->userGroup = $userGroup;

        return $this;
    }

    /**
     * Get the value of userIP
     */
    public function getUserIP()
    {
        return $this->userIP;
    }

    /**
     * Set the value of userIP
     *
     * @return  self
     */
    public function setUserIP($userIP)
    {
        $this->userIP = $userIP;

        return $this;
    }
}
