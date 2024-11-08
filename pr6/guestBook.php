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

$userLogin = $userEmail = $userGroup = null;
if(!empty($_SESSION['login'])){
    $userLogin = $_SESSION['login'];
}
if(!empty($_SESSION['email'])){
    $userEmail = $_SESSION['email'];
}
if(!empty($_SESSION['user_group'])){
    $userGroup = $_SESSION['user_group'];
} else {
    $userGroup = "guest";
    $_SESSION['user_group'] = $userGroup;
}
?>


<!doctype html>
<html lang="uk">

    <head>
        <title>Гостьова книга</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo time(); ?>">
    </head>

    <body>
        <header>
            <h1>Гостьова книга</h1>
        </header>
        <main>
            <section>
                <form action="validateGuestBookForm.php" method="POST" enctype="multipart/form-data">
                    <label for="html">Логін:</label>
                    <input type="text" name="login" value="<?php echo $userLogin; ?>"
                        minlength="3" maxlength="32" required>
                    <label class="required-input" for="html">*</label>
                    <br>
                    <label for="html">Email:</label>
                    <input type="email" name="email" value="<?php echo $userEmail; ?>"
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
                    switch($userGroup){
                        case "administrator":{ ?>
                            <a href="adminPanel.php">Адмін-панель</a><?php
                            break;
                        }
                        case "regestred":{
                            break;
                        }
                        case "guest":{?>
                            <a href="Index.php">Вхід</a>
                            <br>
                            <a href="SignUp.php">Реєстрація</a><?php
                            break;
                        }
                    }
                ?>
                <br>
                <br>
                <br>
                <?php
                    if(!empty($_SESSION['errors'])){
                        try{
                            foreach($_SESSION['errors'] as $error){ ?>
                                <p class="error"><?php echo $error; ?></p>
                            <?php
                            }
                        } catch(Exception $ex){?>
                            <p class="error">Не вдалося вивести помилки</p>
                            <?php
                        }
                    }
                    $_SESSION['errors'] = null;
                    if(!empty($_SESSION['success'])){?>
                        <p class="success"><?php echo $_SESSION['success']; ?></p><?php
                    }
                    $_SESSION['success'] = null;
                ?>
            </section>
        </main>
    </body>

</html>
