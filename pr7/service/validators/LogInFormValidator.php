<?php

declare(strict_types=1);

session_start();

class LogInFormValidator extends Validator {

    private static string $userLogin;
    private static string $userPassword;


    public static function validateData(array $data): array
    {
        if (empty($data['login'])) {
            parent::addError("Логін користувача пустий");
        } else {
            self::$userLogin = parent::getValidatedData($data['login']);

            if (strlen(self::$userLogin) < 3 || strlen(self::$userLogin) > 32) {
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


    private static function doLogIn(string $login, string $password): bool
    {
        try {
            $factoryDAO = DAOFactoryImpl::getInstance();
            $userDAO = $factoryDAO::getUserDAO();

            foreach ($userDAO->getAll() as $user) {
                if ($user->getUserName() === $login && password_verify($password, $user->getPassword())) {
                    $_SESSION['user'] = new CurrentUserDTO($user);
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