<?php

interface DAOFactory {

    public static function getUserDAO(): UserDAO;
    public static function getResponseDAO(): ResponseDAO;
}
