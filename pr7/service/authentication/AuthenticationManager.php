<?php

declare(strict_types=1);

class AuthenticationManager
{
    public static function createGuestUser(): CurrentUserDTO{
        $entity = new \Entity\User();
        $entity->setId(0);
        $entity->setUserName("");
        $entity->setEmail("");
        $entity->setUserGroup('guest');
        return new CurrentUserDTO($entity);
    }
}