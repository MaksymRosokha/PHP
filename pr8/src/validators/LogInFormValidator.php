<?php

declare(strict_types=1);

namespace Rosokha\App\validators;

use Exception;
use Rosokha\App\dto\UserDTO;
use Rosokha\DB\Factory\DAOFactoryImpl;

/**
 *
 */
class LogInFormValidator extends Validator
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
     * @param array $data
     * @return array
     */
    public static function validateData(array $data): array
    {
        if (empty($data['login'])) {
            parent::addError("Логін користувача пустий");
        } else {
            self::$userLogin = parent::getValidatedData($data['login']);

            if (strlen(self::$userLogin) < 2 || strlen(self::$userLogin) > 32) {
                parent::addError("Недопустима довжина символів логіну");
            }
            if (!preg_match("/^[0-9a-zA-Z-' ]*$/", self::$userLogin)) {
                parent::addError("Для логіну можна використовувати тільки літери латинського алфавіту та цифри");
            }
        }

        if (empty($data['password'])) {
            parent::addError("Логін користувача пустий");
        } else {
            self::$userPassword = parent::getValidatedData($data['password']);

            if (strlen(self::$userPassword) < 6 || strlen(self::$userPassword) > 32) {
                parent::addError("Недопустима довжина символів паролю");
            }
        }

        self::doLogIn(login: self::$userLogin, password: self::$userPassword);

        return parent::getErrors();
    }

    /**
     * @param string $login
     * @param string $password
     * @return bool
     */
    private static function doLogIn(string $login, string $password): bool
    {
        try {
            $factoryDAO = DAOFactoryImpl::getInstance();
            $userDAO = $factoryDAO::getUserDAO();

            foreach ($userDAO->getAll() as $user) {
                if ($user->getLogin() === $login && password_verify($password, $user->getPassword())) {
                    session_start();
                    $_SESSION['user'] = new UserDTO($user);
                    return true;
                }
            }
            parent::addError("Логін або пароль невірні");
        } catch (Exception $ex) {
            parent::addError("Не вдалося здійснити вхід");
        }
        return false;
    }

}