<?php

declare(strict_types=1);
session_start();
setcookie("userName", " 1", time() + (86400 * 30), "/");
setcookie("password", " 1", time() + (86400 * 30), "/");
setcookie("isRememberMe", "No", time() + (86400 * 30), "/");


$defaultPassword = $defaultLogin = "";
if(isset($_SESSION['userName']) && !empty($_SESSION['userName']) &&
    isset($_SESSION['password']) && !empty($_SESSION['password'])){
    $defaultLogin = $_SESSION['userName'];
    $defaultPassword = $_SESSION['password'];
}
elseif (isset($_COOKIE['userName']) && !empty($_COOKIE['userName']) &&
    isset($_COOKIE['password']) && !empty($_COOKIE['password']) &&
    isset($_COOKIE['isRememberMe']) && $_COOKIE['isRememberMe'] === "Yes"){
    $defaultLogin = $_COOKIE['userName'];
    $defaultPassword = $_COOKIE['password'];
}

$errors = [];
?>

<!doctype html>
<html lang="uk">
    <head>
        <title>Аутентифікація</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/x-icon" href="images/icons/Log%20in.png">

        <style>
            h1 {
                text-align: center;
            }
            fieldset{
                background: #c486f8;
                border-color: #4201a6;
            }
            body{
                background: #8400ff;
            }
            input{
                background: #e2c7f8;
                border-color: #000000;
            }
            span{
                color: #00e1ff;
            }
            strong{
                color: red;
            }

        </style>

    </head>
    <body>
        <header>
            <h1>Аутентифікація</h1>
        </header>
        <main>
            <section>

                <form method="post" action="checkLogIn.php">
                    <fieldset>
                        <legend>Ваші дані для аутентефікації</legend>
                        <?php
                            $errors = !empty($_SESSION['error']) ? $_SESSION['error'] : $errors ;
                            foreach ($errors as $error){ ?>
                        <span><strong>
                                <?php echo $error . "<br>";
                                $_SESSION['error'] = null;
                            }?>
                            </strong></span><br>

                        Логін:<input type="text" name="userName" value="<?php echo $defaultLogin; ?>"
                               minlength="2" maxlength="200" required><br>
                        Пароль:<input type="password" name="password" value="<?php echo $defaultPassword; ?>"
                               minlength="6" maxlength="100" required><br>
                        <input type="checkbox" name="isRememberMe">Запам'ятати мене<br>

                        <input type="submit">
                    </fieldset>
                    <br>
                    У вас не має аккаунту?
                    <a href="signUp.php" target="_self"><span>Зареєструватися</span></a>

                </form>
            </section>
        </main>
        <footer>

        </footer>
    </body>
</html>