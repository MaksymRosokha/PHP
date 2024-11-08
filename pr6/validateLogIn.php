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

$userLogin = $userPassword = null;
$errors = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST["login"])){
        $errors[] = "Логін користувача пустий";
    } else {
        $userLogin = validateData(data: $_POST["login"]);

        if (strlen($userLogin) < 3 || strlen($userLogin) > 32) {
            $errors[] = "Недопустима довжина символів логіну";
        }
        if (!preg_match("/^[0-9a-zA-Z-' ]*$/", $userLogin)) {
            $errors[] = "Для логіну можна використовувати тільки літери латинського алфавіту та цифри";
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
    $errors['Аргументи не передані'];
}

function validateData($data){
    $data = trim($data);//видаляє пробели
    $data = stripslashes($data);//видаляє екранування
    $data = htmlspecialchars($data);
    return $data;
}

function doLogIn(string $login, string $password): bool{
    global $conn, $errors;

    try {
        $sql = "SELECT *
                  FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        while($user = $result->fetch_assoc()){
            if($user['user_password'] !== null){
                if($user['user_name'] === $login && password_verify($password, $user['user_password'])){
                    $isLogIn = true;
                    $_SESSION['login'] = $user['user_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_group'] = $user['user_group'];
                    return true;
                }
            }
        }
        $errors[] = "Логін або пароль невірні";
        return false;
    } catch (Exception $ex){
        $errors[] = "Не вдалося здійснити вхід";
        return false;
    }
}

if(empty($errors) && doLogin(login: $userLogin, password: $userPassword)){
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
        header("Location: Index.php");
    } catch (Exception $ex){
        die("Файл сторінки входу втрачено");
    }
}
