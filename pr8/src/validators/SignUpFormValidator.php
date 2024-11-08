<?php

declare(strict_types=1);

namespace Rosokha\App\validators;

use Exception;
use PDOException;
use Rosokha\App\dto\UserDTO;
use Rosokha\DB\entity\User;
use Rosokha\DB\Factory\DAOFactoryImpl;

/**
 *
 */
class SignUpFormValidator extends Validator
{

    /**
     * @var string
     */
    private static string $userLogin;
    /**
     * @var string
     */
    private static string $userPassword;
    /**
     * @var string
     */
    private static string $userAvatar;

    /**
     * @param array $data
     * @return array
     */
    public static function validateData(array $data): array
    {
        if (empty($data["login"])) {
            parent::addError("Логін користувача пустий");
        } else {
            self::$userLogin = parent::getValidatedData(data: $data["login"]);

            if (strlen(self::$userLogin) < 2 || strlen(self::$userLogin) > 32) {
                parent::addError("Недопустима довжина символів логіну");
            }
            if (!preg_match("/^[0-9a-zA-Z-' ]*$/", self::$userLogin)) {
                parent::addError("Для логіну можна використовувати тільки літери латинського алфавіту та цифри");
            }
        }

        if (empty($data["password"])) {
            parent::addError("Пароль користувача пустий");
        } else {
            self::$userPassword = parent::getValidatedData(data: $data["password"]);

            if (strlen(self::$userPassword) < 6 || strlen(self::$userPassword) > 32) {
                parent::addError("Недопустима довжина символів паролю");
            }
        }

        self::$userAvatar = $data["avatar"];

        if (empty(Validator::getErrors()) && self::checkLoginForUniqueness(login: self::$userLogin)) {
            self::doSignUp(login: self::$userLogin, password: self::$userPassword, avatar: self::$userAvatar);
        }

        return parent::getErrors();
    }

    /**
     * @param string $login
     * @return bool
     */
    private static function checkLoginForUniqueness(string $login): bool
    {
        try {
            $factoryDAO = DAOFactoryImpl::getInstance();
            $userDAO = $factoryDAO::getUserDAO();

            if (empty($userDAO->getByLogin(login: $login))) {
                return true;
            } else {
                parent::addError("Користувач з таким логіном вже існує");
                return false;
            }
        } catch (PDOException $ex) {
            parent::addError($ex->getMessage());
            return false;
        }
    }

    /**
     * @param string $login
     * @param string $password
     * @param string $avatar
     * @return bool
     */
    private static function doSignUp(string $login, string $password, string $avatar): bool
    {
        try {
            $factoryDAO = DAOFactoryImpl::getInstance();
            $userDAO = $factoryDAO::getUserDAO();

            $user = new User();
            $user->setLogin(login: $login);
            $user->setAvatar(avatar: $avatar);
            $user->setPassword(password: password_hash($password, PASSWORD_BCRYPT));
            $newUser = $userDAO->getById($userDAO->insert($user));

            session_start();
            $_SESSION['user'] = new UserDTO($newUser);
            return true;
        } catch (Exception $ex) {
            parent::addError("Не вдалося зареєструвати користувача");
            return false;
        }
    }
}