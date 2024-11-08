<?php

declare(strict_types=1);

session_start();

class GuestBookFormValidator extends Validator
{
    private static CurrentUserDTO $currentUserDTO;
    private static string $userLogin;
    private static string $userEmail;
    private static string $userMessage;
    private static string|null $userFile = null;

    public static function validateData(array $data): array
    {
        if(empty($_SESSION['user'])){
            parent::addError("Не вдалося перевірити інформацію.");
        } else {
            self::$currentUserDTO = $_SESSION['user'];
        }
        if (empty($data["login"])) {
            parent::addError("Логін користувача пустий");
        }
        else {
            self::checkLogin($data["login"]);
        }
        if (empty($data["email"])) {
            parent::addError("Введіть ел. пошту");
        } else {
            self::checkEmail($data["email"]);
        }
        if (empty($data["message"])) {
            parent::addError("Повідомлення пусте");
        } else {
            self::checkMessage($data["message"]);
        }

        self::checkUserFile();

        if(empty(parent::getErrors())) {
            self::sendResponse(userId: self::$currentUserDTO->getId(), login: self::$userLogin,
                email: self::$userEmail, file: self::$userFile, content: self::$userMessage);
        }
        return parent::getErrors();
    }

    public static function checkLogin(string $login): void
    {
            self::$userLogin = parent::getValidatedData(data: $login);

            if (strlen(self::$userLogin) < 3 || strlen(self::$userLogin) > 32) {
                parent::addError("Недопустима довжина символів логіну");
            }
            if (!preg_match("/^[0-9a-zA-Z-' ]*$/", self::$userLogin)) {
                parent::addError("Для логіну можна використовувати тільки літери латинського алфавіту та цифри");
            }
    }

    private static function checkEmail(string $email): void
    {
            self::$userEmail = parent::getValidatedData(data: $email);
            if (strlen(self::$userEmail) > 64) {
                parent::addError("Недопустима довжина символів email");
            }
            if (!filter_var(self::$userEmail, FILTER_VALIDATE_EMAIL)) {
                parent::addError("Не правильний формат електронної пошти");
            }

    }

    private static function checkMessage(string $message): void
    {
        self::$userMessage = parent::getValidatedData(data: $message);
        if (strlen(self::$userMessage) < 10 || strlen(self::$userMessage) > 5000) {
            parent::addError("Недопустима довжина повідомлення");
        }
    }


    private static function checkUserFile(): void
    {
        if (isset($_FILES['file']) && !empty($_FILES["file"])) {
            $name = $_FILES['file']['name'];

            if ($name !== "") {
                $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/data/images or files/";
                $targetFile = $targetDir . basename($name);
                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                if (file_exists($targetFile)) {
                    parent::addError("Картинка або текстовий файл з такою назвою вже існує");
                }
                switch ($fileType) {
                    case "txt":
                    {
                        if ($_FILES["file"]["size"] > 819200) {
                            parent::addError("Текстовий файл не може бути важчим ніж 100кб");
                        }
                        break;
                    }
                    case "png":{}
                    case "gif":{}
                    case "jpg":
                    {
                        [$width, $height, $type, $attr] = getimagesize($_FILES["file"]["tmp_name"]);

                        if ($width > 800 || $height > 600) {
                            $image = new Imagick($_FILES["file"]["tmp_name"]);
                            while ($width > 800 || $height > 600) {
                                if ($width < 10 || $height < 10) {
                                    parent::addError("Розміри картинки неможливо коректно пропорціонально зментиши до підходячого формату 800х600px");
                                    break;
                                }
                                $width--;
                                $height--;
                            }
                            if (empty(parent::getErrors())) {
                                $image->adaptiveResizeImage($width, $height);
                                $image->writeImage($targetFile);
                            }
                        }
                        break;
                    }
                    default:
                    {
                        parent::addError("Картинка або текстовий файл повинний мати одне з наступних роз-ширень: \"png\", \"jpg\",\"gif\", \"txt\"");
                    }
                }

                if (empty(parent::getErrors())) {
                    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                        parent::addError("Вибачте, не вдалося завантажити файл");
                        return;
                    }
                    self::$userFile = $name;
                }
            }
        }
    }

    private static function isLoginExist(string $login): bool
    {
        try {
            $factoryDAO = DAOFactoryImpl::getInstance();
            $userDAO = $factoryDAO::getUserDAO();

            if(empty($userDAO->getByLogin(login: $login))){
                return true;
            } else {
                return false;
            }

        } catch (PDOException $ex) {
            parent::addError($ex->getMessage());
            return false;
        }
    }


    private static function addUserIfNotExist(string $login, string $email): bool
    {
        try {
            if ( self::isLoginExist($login)) {
                $factoryDAO = DAOFactoryImpl::getInstance();
                $userDAO = $factoryDAO::getUserDAO();

                $user = new Entity\User();
                $user->setUserName(userName: $login);
                $user->setEmail(email: $email);
                $user->setUserGroup(userGroup: "guest");
                $user->setUserIP(userIP: getenv("REMOTE_ADDR"));
                $userDAO->save($user);
            }
            return true;
        } catch (Exception $ex) {
            parent::addError("Не вдалося додати користувача");
            return false;
        }
    }

    private static function sendResponse(int $userId, string $login, string $email, string|null $file, string $content): bool
    {
        try {
            if (self::addUserIfNotExist($login, $email)) {
                $factoryDAO = DAOFactoryImpl::getInstance();
                $responseDAO = $factoryDAO::getResponseDAO();

                $response = new Entity\Response();
                $response->setUserId($userId);
                $response->setImageOrFile($file);
                $response->setContent($content);
                $response->setDateOfWriting(date("Y-m-d H:i:s"));

                $responseDAO->save($response);

                return true;
            } else {
                throw new Exception();
            }
        } catch (Exception $ex) {
            parent::addError("Виникла помилка під час відправлення повідомлення");
            return false;
        }
    }

}
