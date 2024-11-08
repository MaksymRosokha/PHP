<?php

declare(strict_types=1);
session_start();

$file = fopen("users/users.txt", "r");
$countOfUsers = 0;
$users = array([],[]);
$currentUser = array();
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

$errors = [];

function isLogIn(): bool{
    global $users, $errors, $countOfUsers, $currentUser;
    for ($i = 0; $i < $countOfUsers; $i++) {
        if($users[$i][0] == inputValidate(data: $_POST["userName"]) &&
            password_verify($_POST["password"], $users[$i][1])){
            $currentUser = $users[$i];
            return true;
        }
    }
    $errors[] = "Не правильний логін або пароль";
    return false;
}

function checkLoginAndPassword(): bool
{
    global $errors, $login;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST)) {
            if (empty($_POST["userName"])) {
                $errors[] = "Логін пустий";
                return false;
            } else {
                $login = inputValidate(data: $_POST["userName"]);
                if (strlen($login) < 2 || strlen($login) > 200) {
                    $errors[] = "Недопустима довжина символів логіну";
                    return false;
                }
                if (!preg_match("/^[a-zA-Z-' ]*$/", $login)) {
                    $errors[] = "Для логіну потрібно використовувати тільки літери латинського алфавіту та \"пробіли\"";
                    return false;
                }
            }

            return true;
        } else {
            $errors[] = "Введіть дані";
            return false;
        }
    }
    $errors[] = "Виникла помилка";
    return false;
}

function inputValidate($data)
{
    $data = trim($data);//видаляє пробели
    $data = stripslashes($data);//видаляє екранування
    $data = htmlspecialchars($data);
    return $data;
}

function doLogIn(){
    global $currentUser;
    $_SESSION['userName'] = $currentUser[0];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['avatar'] = $currentUser[2];
    $_SESSION['group'] = $currentUser[3];
    $_SESSION['email'] = $currentUser[4];
    $_SESSION['phoneNumber'] = $currentUser[5];
    $_SESSION['gender'] = $currentUser[6];
    saveCookie();
}

function saveCookie(){
    if(isset($_POST['isRememberMe'])){
        if($_POST['isRememberMe'] === "on"){
            setcookie("userName", $_SESSION['userName'], time() + 86400 * 30, "/");
            setcookie("password", $_SESSION['password'], time() + 86400 * 30, "/");
            setcookie("isRememberMe", "Yes", time() + (86400 * 30), "/");
        }
    }
}

if(checkLoginAndPassword() && isLogIn()){
    $_SESSION['login'] = $_POST;
    doLogIn();
    fclose($file);
    header( "Location: content.php" );
}
else {
    $_SESSION['error'] = $errors;
    fclose($file);
    header( "Location: Index.php" );
}