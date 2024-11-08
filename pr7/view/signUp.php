<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . "/entity/User.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dao/UserDAO.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/impl/UserDAOImpl.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/ConnectionManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/factory/DAOFactory.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/factory/DAOFactoryImpl.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/dto/CurrentUserDTO.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/authentication/AuthenticationManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/validators/Validator.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/validators/SignUpFormValidator.php";

session_start();

$errors = [];

if(isset($_POST) && !empty($_POST)) {
    $errors = SignUpFormValidator::validateData($_POST);
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
        <title>Реєстрація</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../data/css/style.css?v=<?php echo time(); ?>">
    </head>

    <body>
        <header>
            <h1>Реєстрація</h1>
        </header>
        <main>
            <section>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <label for="html">Логін:</label>
                    <input type="text" name="login" required
                        minlength="3" maxlength="32">
                    <label class="required-input" for="html">*</label>
                    <br>
                    <label for="html">Email:</label>
                    <input type="email" name="email" required maxlength="64">
                    <label class="required-input" for="html">*</label>
                    <br>
                    <label for="html">Пароль:</label>
                    <input type="password" name="password" required
                        minlength="6" maxlength="32">
                    <label class="required-input" for="html">*</label>
                    <br>
                    <input type="submit">
                </form>
                <a href="guestBook.php<?php $_SESSION['user'] = AuthenticationManager::createGuestUser(); ?>">Пропустити реєстрацію</a>
                <br>
                <br>
                <br>
                <?php
                    foreach($errors as $error){ ?>
                        <p class="error"><?php echo $error ?></p>
                        <?php
                    }

                    $errors = [];
                    SignUpFormValidator::clearErrors();
                ?>
            </section>
        </main>
    </body>

</html>
