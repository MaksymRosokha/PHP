<?php

interface DAOFactory {

    public static function getUserDAO(): UserDAO;
    //Сюди добавляємо інші ДАО об'єкти
}
