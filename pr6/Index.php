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
?>


<!doctype html>
<html lang="uk">

    <head>
        <title>Вхід</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    </head>

    <body>
        <header>
            <h1>Вхід</h1>
        </header>
        <main>
            <section>
                <form action="validateLogIn.php" method="POST">
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
                <label for="html">У вас не має аккаунту? </label><a href="signUp.php">Зареєструватися</a>
                <br>
                <a href="guestBook.php<?php $_SESSION['login'] = $_SESSION['email'] = $_SESSION['user_group'] = null; ?>">Пропустити авторизацію</a>
                <br>
                <br>
                <br>
                <?php
                    if(isset($_SESSION['errors']) && !empty($_SESSION['errors'])){
                        try{
                            foreach($_SESSION['errors'] as $error){ ?>
                                <p class="error"><?php echo $error ?></p>
                            <?php
                            }
                        } catch(Exception $ex){?>
                            <p class="error">Не вдалося вивести помилки</p>
                            <?php
                        }
                    }
                    $_SESSION['errors'] = null;
                ?>
            </section>
        </main>
    </body>

</html>
