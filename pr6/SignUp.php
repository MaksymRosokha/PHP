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
        <title>Реєстрація</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    </head>

    <body>
        <header>
            <h1>Реєстрація</h1>
        </header>
        <main>
            <section>
                <form action="validateSignUp.php" method="POST">
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
                <a href="guestBook.php<?php $_SESSION['login'] = $_SESSION['email'] = $_SESSION['user_group'] = null;;?>">Пропустити реєстрацію</a>
                <br>
                <br>
                <br>
                <?php
                    if(!empty($_SESSION['errors'])){
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
