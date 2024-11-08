<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/ConnectionManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/entity/User.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dao/UserDAO.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/impl/UserDAOImpl.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/entity/Response.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dao/ResponseDAO.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/impl/ResponseDAOImpl.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/factory/DAOFactory.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/factory/DAOFactoryImpl.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/dto/CurrentUserDTO.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/dto/ResponseDTO.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/validators/Validator.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/validators/GuestBookFormValidator.php";

session_start();

$user = null;
$errors = [];
$success = '';

if(!empty($_SESSION['user'])){
    $user = $_SESSION['user'];
} else {
    die("Не вдалося розпізнати користувача");
}

if(isset($_POST) && !empty($_POST)) {
    $errors = GuestBookFormValidator::validateData($_POST);
    if(empty($errors)){
        $success = "Відгук успішно відправлено";
    }
}

?>


<!doctype html>
<html lang="uk">

    <head>
        <title>Гостьова книга</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../data/css/style.css?v=<?php echo time(); ?>">
    </head>

    <body>
        <header>
            <h1>Гостьова книга</h1>
        </header>
        <main>
            <section>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                    <label for="html">Логін:</label>
                    <input type="text" name="login" value="<?php echo $user->getUserName(); ?>"
                        minlength="3" maxlength="32" required>
                    <label class="required-input" for="html">*</label>
                    <br>
                    <label for="html">Email:</label>
                    <input type="email" name="email" value="<?php echo $user->getEmail(); ?>"
                        maxlength="64" required>
                    <label class="required-input" for="html">*</label>
                    <br>
                    <label for="html">Картинка або текстовий файл:</label>
                    <input type="file" name="file">
                    <br>
                    <label for="html">Повідомлення:</label>
                    <textarea name="message" minlength="10" maxlength="5000" required></textarea>
                    <label class="required-input" for="html">*</label>
                    <br>
                    <input type="submit">
                </form>
                <br>
                <br>
                <br>
                <?php
                    switch($user->getUserGroup()){
                        case "administrator":{ ?>
                            <a href="adminPanel.php">Адмін-панель</a><?php
                            break;
                        }
                        case "regestred":{
                            break;
                        }
                        case "guest":{?>
                            <a href="../index.php">Вхід</a>
                            <br>
                            <a href="signUp.php">Реєстрація</a><?php
                            break;
                        }
                    }
                ?>
                <br>
                <br>
                <br>
                <?php
                    foreach($errors as $error){ ?>
                        <p class="error"><?php echo $error; ?></p> <?php
                    }

                    $errors = [];
                    GuestBookFormValidator::clearErrors(); ?>

                <p class="success"><?php echo $success; ?></p>
                <?php $success = ''; ?>
            </section>
        </main>
    </body>

</html>
