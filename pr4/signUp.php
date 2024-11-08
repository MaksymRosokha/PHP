<?php

declare(strict_types=1);

$errors = [];

?>

<!doctype html>
<html lang="uk">
  <head>
      <title>Реєстрація</title>
      <meta charset="UTF-8">
      <link rel="icon" type="image/x-icon" href="images/icons/Sign%20up.ico">

      <style>
          h1, h3{
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
          textarea{
              background: #e2c7f8;
              border-color: #000000;
              resize: none;
          }
          select{
              background: #e2c7f8;
              border-color: #000000;
          }
          .error{
              color: red;
          }
          #authorization {
              background-color: blueviolet;
          }

      </style>

  </head>
  <body>
    <header>
        <h1>Реєстрація</h1>
    </header>

    <main>
        <section>
            <form action="checkSignUp.php" method="post" enctype = "multipart/form-data">
                <fieldset>
                    <legend>Ваші дані для реєстрації</legend>

                    <?php
                    session_start();
                    $errors = !empty($_SESSION['error']) ? $_SESSION['error'] : $errors ;
                    foreach ($errors as $error){ ?>
                    <span><strong class="error">
                            <?php echo $error . "<br>";
                    }
                    $_SESSION['error'] = null;?>
                        </strong></span><br>

                    Логін:<input type="text" name="userName" maxlength="200" minlength="2" required><br>
                    Ел. пошта:<input type="email" name="email" maxlength="400" required><br>
                    Номер телефону:<input type="tel" name="phoneNumber" required
                                          pattern="[0-9]{2} [0-9]{3} [0-9]{4}">(формат 12 345 6789)<br>
                    Пароль<input type="password" name="password" maxlength="100" minlength="6" required><br>

                    Стать:<br>
                    <input type="radio" name="gender" value="чоловік" checked>Чоловік<br>
                    <input type="radio" name="gender" value="жінка">Жінка<br>
                    <input type="radio" name="gender" value="не визначився">Не визначився<br>
                    <input type="radio" name="gender" value="інше">Інше<br>

                    Аватарка:<input type="file" name="avatar">
                    <br>
                    <br>
                    <div id="authorization">
                        <h3>Авторизація</h3>
                        Група:<br>
                        <input type="radio" name="group" value="user" checked>Користувач<br>
                        <input type="radio" name="group" value="moder">Модератор<br>
                        <input type="radio" name="group" value="admin">Адміністратор<br>
                    </div>
                    <br>
                    <br>
                    <input type="submit">
                    <br>
                </fieldset>
            </form>
        </section>
    </main>

  </body>
</html>
