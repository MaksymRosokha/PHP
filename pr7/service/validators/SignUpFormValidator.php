<?php

declare(strict_types=1);

session_start();

class SignUpFormValidator extends Validator {

    private static string $userLogin;
    private static string $userEmail;
    private static string $userPassword;

    public static function validateData(array $data): array {

        if (empty($data["login"]))
        {
            parent::addError("Логін користувача пустий");
        }
        else {
            self::$userLogin = parent::getValidatedData(data: $data["login"]);

            if (strlen(self::$userLogin) < 3 || strlen(self::$userLogin) > 32) {
                parent::addError("Недопустима довжина символів логіну");
            }
            if (!preg_match("/^[0-9a-zA-Z-' ]*$/", self::$userLogin)) {
                parent::addError("Для логіну можна використовувати тільки літери латинського алфавіту та цифри");
            }
        }

        if (empty($data["email"])) {
            parent::addError("Введіть ел. пошту");
        } else {
            self::$userEmail = parent::getValidatedData(data: $data["email"]);
            if (strlen(self::$userEmail) > 64) {
                parent::addError("Недопустима довжина символів email");
            }
            if (!filter_var(self::$userEmail, FILTER_VALIDATE_EMAIL)) {
                parent::addError("Не правильний формат електронної пошти");
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

        if(self::checkLoginForUniqueness(login: self::$userLogin) && empty(Validator::getErrors())){
            self::doSignUp(login: self::$userLogin, email: self::$userEmail, password: self::$userPassword);
        }

        return parent::getErrors();
    }


    private static function checkLoginForUniqueness(string $login): bool
    {
        try {
            $factoryDAO = DAOFactoryImpl::getInstance();
            $userDAO = $factoryDAO::getUserDAO();

            if(empty($userDAO->getByLogin(login: $login))){
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

    private static function doSignUp(string $login, string $email, string $password): bool
    {
        try {
            $factoryDAO = DAOFactoryImpl::getInstance();
            $userDAO = $factoryDAO::getUserDAO();

            $user = new Entity\User();
            $user->setUserName(userName: $login);
            $user->setEmail(email: $email);
            $user->setPassword(password: password_hash($password, PASSWORD_BCRYPT));
            $user->setUserGroup(userGroup: "regestred");
            $user->setUserIP(userIP: getenv("REMOTE_ADDR"));
            $newUser = $userDAO->getById($userDAO->save($user));

            $_SESSION['user'] = new CurrentUserDTO($newUser);
            return true;
        } catch (Exception $ex) {
            parent::addError("Не вдалося зареєструвати користувача");
            return false;
        }
    }
}