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

$userLogin = $userEmail = $userPassword = null;
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
    if(empty($_POST["password"])){
        $errors[] = "Пароль користувача пустий";
    } else {
        $userPassword = validateData(data: $_POST["password"]);

        if (strlen($userPassword) < 6 || strlen($userPassword) > 32) {
            $errors[] = "Недопустима довжина символів паролю";
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

function checkLoginForUniqueness(string $login): bool{
    global $conn, $errors;
    try{
        $sql = "SELECT user_name
                  FROM users
                 WHERE user_name = ? ;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 0){
            return true;
        } else {
            $errors[] = "Користувач з таким логіном вже існує";
            return false;
        }
    } catch (Exception $ex){
        $errors[] = "Не вдалося перевірити логін";
        return false;
    }
}

function doSignUp(string $login, string $email, string $password): bool{
    GLOBAL $conn, $errors;
    try{
        $sql = "INSERT INTO users (user_name, email, user_password, user_group, ip)
                VALUES (?, ?, ?, ?, ?);";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("sssss", $user_name, $user_email, $user_password, $user_group, $user_ip);
            $user_name = $login;
            $user_email = $email;
            $user_password = password_hash($password, PASSWORD_BCRYPT);
            $user_group = "regestred";
            $user_ip = getIp();
            $stmt->execute();
            $stmt->close();
            return true;
        } else {
            throw new Exception();
        }
    } catch (Exception $ex){
        $errors[] = "Не вдалося зареєструвати";
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

if(empty($errors) && checkLoginForUniqueness(login: $userLogin) && doSignUp(login: $userLogin, email: $userEmail, password: $userPassword)){
    $_SESSION['login'] = $userLogin;
    $_SESSION['email'] = $userEmail;
    $_SESSION['user_group'] = "regestred";
    try{
        $conn->close();
        header("Location: guestBook.php");
    } catch (Exception $ex){
        die("Файл гостьової книги втрачено");
    }
} else {
    $_SESSION['errors'] = $errors;
    try{
        $conn->close();
        header("Location: SignUp.php");
    } catch (Exception $ex){
        die("Файл сторінки реєстрації втрачено");
    }
}
