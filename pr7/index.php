<?php

declare(strict_types=1);

require_once "entity/User.php";
require_once "dao/UserDAO.php";
require_once "impl/UserDAOImpl.php";
require_once "util/ConnectionManager.php";
require_once "factory/DAOFactory.php";
require_once "factory/DAOFactoryImpl.php";
require_once "service/dto/CurrentUserDTO.php";
require_once "service/authentication/AuthenticationManager.php";
require_once "service/validators/Validator.php";
require_once "service/validators/LogInFormValidator.php";

session_start();

$errors = [];

if(isset($_POST) && !empty($_POST)) {
    $errors = LogInFormValidator::validateData($_POST);
    if(empty($errors)){
        try {
            header("Location: ../view/guestBook.php");
            die();
        } catch (Exception $exception){
            die("Файл гостьової сторінки втрачено");
        }
    }
}

?>


<!doctype html>
<html lang="uk">

    <head>
        <title>Вхід</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="data/css/style.css?v=<?php echo time(); ?>">
    </head>

    <body>
        <header>
            <h1>Вхід</h1>
        </header>
        <main>
            <section>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <label for="html">Логін:</label>
                    <input type="text" name="login" required
                        minlength="3" maxlength="32">
                    <label class="required-input" for="html">*</label>
                    <br>
                    <label for="html">Пароль:</label>
                    <input type="password" name="password" required
                        minlength="6" maxlength="32">
                    <label class="required-input" for="html">*</label>
                    <br>
                    <input type="submit">
                </form>
                <label for="html">У вас не має аккаунту? </label><a href="view/signUp.php">Зареєструватися</a>
                <br>
                <a href="view/guestBook.php<?php $_SESSION['user'] = AuthenticationManager::createGuestUser(); ?>">Пропустити авторизацію</a>
                <br>
                <br>
                <br>
                <?php
                    foreach($errors as $error){ ?>
                        <p class="error"><?php echo $error ?></p> <?php
                    }
                    $errors = [];
                    LogInFormValidator::clearErrors();
                ?>
            </section>
        </main>
    </body>

</html>
