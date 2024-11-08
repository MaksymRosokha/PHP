<?php

declare(strict_types=1);

session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "guest_book";
$conn = null;

try{
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception();
    }
} catch (Exception $ex){
    die("Помилка підключення до бази даних");
}

$userID = $userLogin = $userEmail = $userFile = $userText = null;
$errors = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST["login"])){
        $errors[] = "Логін користувача пустий";
    } else {
        $userLogin = validateData(data: $_POST["login"]);

        if (strlen($userLogin) < 3 || strlen($userLogin) > 32) {
            $errors[] = "Недопустима довжина символів логіну";
        }
        if (!preg_match("/^[0-9a-zA-Z-' ]*$/",$userLogin)) {
            $errors[] = "Для логіну можна використовувати тільки літери латинського алфавіту та цифри";
        }
    }
    if (empty($_POST["email"])) {
        $errors[] = "Введіть ел. пошту";
    } else {
        $userEmail = validateData(data: $_POST["email"]);
        if (strlen($userEmail) > 64) {
            $errors[] = "Недопустима довжина символів email";
        }
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Не правильний формат електронної пошти";
        }
    }
    checkUserFile();

    if(empty($_POST["message"])){
        $errors[] = "Повідомлення пусте";
    } else {
        $userText = validateData(data: $_POST["message"]);
        if (strlen($userText) < 10 || strlen($userText) > 5000) {
            $errors[] = "Недопустима довжина повідомлення";
        }
    }
} else {
    $errors[] = "Аргументи не передані";
}

function validateData($data){
    $data = trim($data);//видаляє пробели
    $data = stripslashes($data);//видаляє екранування
    $data = htmlspecialchars($data);
    return $data;
}

function checkUserFile(){
    global $errors, $userFile;

    if(isset($_FILES['file']) && !empty($_FILES["file"])){

        $name = $_FILES['file']['name'];

        if ($name !== "") {
            $targetDir = "images or files/";
            $targetFile = $targetDir . basename($name);
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (file_exists($targetFile)) {
                $errors[] = "Картинка або текстовий файл з такою назвою вже існує";
            }
            switch ($fileType) {
                case "txt":{
                    if ($_FILES["file"]["size"] > 819200) {
                        $errors[] = "Текстовий файл не може бути важчим ніж 100кб";
                    }
                    break;
                }
                case "png":{}
                case "gif":{}
                case "jpg":{
                    list($width, $height, $type, $attr) = getimagesize($_FILES["file"]["tmp_name"]);

                    if($width > 800 || $height > 600){
                        $image = new Imagick($_FILES["file"]["tmp_name"]);
                        while($width > 800 || $height > 600) {
                            if($width < 10 || $height < 10){
                                $errors[] = "Розміри картинки неможливо коректно пропорціонально зментиши до підходячого формату 800х600px";
                                break;
                            }
                            $width--;
                            $height--;
                        }
                        if (empty($errors)) {
                            $image->adaptiveResizeImage($width, $height);
                            $image->writeImage ($_SERVER['DOCUMENT_ROOT'] . "/" . $targetFile);
                        }
                    }
                    break;
                }
                default:{
                    $errors[] = "Картинка або текстовий файл повинний мати одне з наступних роз-ширень: \"png\", \"jpg\",\"gif\", \"txt\"";
                }
            }

            if (empty($errors)) {
                if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                    $errors[] = "Вибачте, не вдалося завантажити файл";
                    return;
                }
                $userFile = $targetFile;
            }
        }
    }
}

function isLoginUniqueness(string $login): bool{
    global $conn, $userID;
    try{
        $sql = "SELECT id
                  FROM users
                 WHERE user_name = ? ;";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            if($result->num_rows === 0){
                return true;
            } else {
                while($user = $result->fetch_assoc()){
                    $userID = $user['id'];
                    return false;
                }
            }
        }
        throw new Exception();
    } catch (Exception $ex){
        throw new Exception();
    }
}

function addUserIfNotExist(string $login, string $email): bool{
    GLOBAL $conn, $errors, $userID;
    try{
        if(isLoginUniqueness($login)){
            $sql = "INSERT INTO users (user_name, email, user_group, ip)
                    VALUES (?, ?, ?, ?);";
            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("ssss", $user_name, $user_email, $user_group, $user_ip);
                $user_name = $login;
                $user_email = $email;
                $user_group = "guest";
                $user_ip = getIp();
                $stmt->execute();
                $userID = $stmt->insert_id;
                $stmt->close();
            } else {
                throw new Exception();
            }
        }
        return true;
    } catch (Exception $ex){
        $errors[] = "Не вдалося додати користувача";
        return false;
    }
}

function getIp(): string {
    $keys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR'
    ];
    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = trim(end(explode(',', $_SERVER[$key])));
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
}

function sendMessage(string $login, string $email, string|null $file, string $text) : bool {
    GLOBAL $conn, $errors, $userID;
    try {
        if(addUserIfNotExist(login: $login, email: $email)){
            if($file !== null){
                $sql = "INSERT INTO responses(id_user, image_or_file, content, date_of_writing)
                        VALUES (?, ?, ?, ?);";
                if($stmt = $conn->prepare($sql)){
                    $stmt->bind_param("isss", $id, $imageOrFile, $content, $dateOfWriting);
                    $id = $userID;
                    $imageOrFile = basename($file);
                    $content = $text;
                    $dateOfWriting = date("Y-m-d H:i:s");
                    $stmt->execute();
                    $stmt->close();
                }
            } else {
                $sql = "INSERT INTO responses(id_user, content, date_of_writing)
                        VALUES (?, ?, ?);";
                if($stmt = $conn->prepare($sql)){
                    $stmt->bind_param("iss", $id, $content, $dateOfWriting);
                    $id = $userID;
                    $content = $text;
                    $dateOfWriting = date("Y-m-d H:i:s");
                    $stmt->execute();
                    $stmt->close();
                }
            }
            return true;
        } else {
            return false;
        }
    } catch (Exception $ex) {
        $errors[] = "Виникла помилка під час відправлення повідомлення";
        return false;
    }
}

if(empty($errors) && sendMessage(login: $userLogin, email: $userEmail, file: $userFile, text: $userText)){
    $_SESSION['success'] = "Повідомлення успішно відправлено)";
    try {
        $conn->close();
        header("Location: guestBook.php");
    } catch (Exception $ex){
        die("Файл гостьової книги втрачено");
    }
} else {
    $_SESSION['errors'] = $errors;
    try {
        $conn->close();
        header("Location: guestBook.php");
    } catch (Exception $ex){
        die("Файл гостьової книги втрачено");
    }
}
