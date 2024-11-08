<?php

declare(strict_types=1);
session_start();

$file = fopen("users/users.txt", "a+");
$countOfUsers = 0;
$users = array([],[]);
$errors = [];
$line = "";

for ($i = 0; !feof($file);){
    $s = fgetc($file);
    $line .= $s;
    if($s === "\n"){
        $users[$i] = explode("||", $line);
        $i++;
        $countOfUsers++;
        $line = "";
    }
}

function isSignUp(): bool{
    global $users, $errors, $countOfUsers;
    for ($i = 0; $i < $countOfUsers; $i++) {
        if ($users[$i][0] === inputValidate(data: $_POST["userName"])) {
            $errors[] = "Введений логін вже існує";
            return false;
        }
    }
    return true;
}

function checkData(): bool
{
    global $errors;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty($_POST["userName"])){
            $errors[] = "Логін користувача пустий";
        } else {
            $userName = inputValidate(data: $_POST["userName"]);

            if (strlen($userName) < 2 || strlen($userName) > 200) {
                $errors[] = "Недопустима довжина символів логіну";
            }
            if (!preg_match("/^[a-zA-Z-' ]*$/",$userName)) {
                $errors[] = "Для логіну можна використовувати тільки літери латинського алфавіту";
            }
        }
        if (empty($_POST["email"])) {
            $errors[] = "Введіть ел. пошту";
        } else {
            $email = inputValidate(data: $_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Не правильний формат електронної пошти";
            }
        }

        if (!isset($_POST["gender"]) OR empty($_POST["gender"])) {
            $errors[] = "Стать обов'язкова";
        }
        else{
            if($_POST["gender"] !== "чоловік" &&
                $_POST["gender"] !== "жінка" &&
                $_POST["gender"] !== "не визначився" &&
                $_POST["gender"] !== "інше"){
                $errors[] = "Вибрана стать не відповідає ніодному з зазначений варіантів";
            }
        }

        if (!isset($_POST["group"]) OR empty($_POST["group"])) {
            $errors[] = "Група обов'язкова";
        }
        else{
            if($_POST["group"] !== "user" &&
                $_POST["group"] !== "moder" &&
                $_POST["group"] !== "admin"){
                $errors[] = "Вибрана група не відповідає ніодному з зазначений варіантів";
            }
        }

        checkAvatar();


        if(empty($errors)) {
            return true;
        }
        else{
            return false;
        }
    }
    $errors[] = "Виникла помилка";
    return false;
}

function checkAvatar(): bool{
    global $errors;

    if(isset($_FILES['avatar'])){

        $name = $_FILES['avatar']['name'];
        echo $name;

        if ($name !== "") {
            $targetDir = "users/avatars/";
            $targetFile = $targetDir . basename($name);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (file_exists($targetFile)) {
                $errors[] = "Зображення з такою назвою вже існує";
            }
            if ($_FILES["avatar"]["size"] > 10000000) {
                $errors[] = "Зображення не може бути важчим ніж 10мб";
            }
            switch ($imageFileType) {
                case "png":
                {
                }
                case "jpg":
                {
                    break;
                }
                default:
                {
                    $errors[] = "Зображення повинно мати одне з наступних розширень: \"png\", \"jpg\"";
                }
            }

            if (empty($errors)) {
                if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {
                    $errors[] = "Вибачте, не вдалося завантажити файл";
                }
                return true;
            } else {
                return false;
            }
        }
        else {
            $errors[] = "Зображення не вибране";
            return false;
        }
    }
    return false;
}

function inputValidate($data)
{
    $data = trim($data);//видаляє пробели
    $data = stripslashes($data);//видаляє екранування
    $data = htmlspecialchars($data);
    return $data;
}

function addNewUser(){
    global $file;
    fwrite($file, $_POST['userName'] . "||" . password_hash($_POST['password'], PASSWORD_BCRYPT) . "||" .
        $_FILES['avatar']['name'] . "||" . $_POST['group'] . "||" . $_POST['email'] . "||" . $_POST['phoneNumber'] .
        "||" . $_POST['gender'] . "\r\n");
}

if(isSignUp() && checkData()){
    addNewUser();
    $_SESSION['userName'] = $_POST['userName'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['avatar'] = $_FILES['avatar']['name'];
    $_SESSION['group'] = $_POST['group'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['phoneNumber'] = $_POST['phoneNumber'];
    $_SESSION['gender'] = $_POST['gender'];
    fclose($file);
    header( "Location: content.php" );
}
else {
    $_SESSION['error'] = $errors;
    fclose($file);
    header( "Location: signUp.php" );
}