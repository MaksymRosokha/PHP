<?php

declare(strict_types=1);
session_start();

?>


<!doctype html>
<html lang="uk">
  <head>
      <title>Сторінка користувача</title>
      <meta charset="UTF-8">
      <link rel="icon" type="image/x-icon" href="images/icons/Icon.ico">

      <style>
          h1, h2{
              text-align: center;
          }
          body{
              background: #8400ff;
          }
          header{
              background: #01a6a1;
          }
          hr{
              border-color: #000000;
          }
          #avatar {
              width: 20%;
              height: 20%;
          }
      </style>
  </head>
  <body>
    <header>
        <h1>Сторінка користувача</h1>
        <hr>
    </header>
    <main>
        <section>
            <h2 id="aboutUser">Ваші дані</h2>
            <img id="avatar" src="users/avatars/<?php echo $_SESSION['avatar']; ?>">
            <br>
            <b>Логін:</b><?php echo $_SESSION["userName"];?><br>
            <b>Ел. пошта:</b><?php echo $_SESSION["email"];?><br>
            <b>Номер телефону:</b><?php echo $_SESSION["phoneNumber"];?><br>
            <b>Стать:</b><?php echo $_SESSION["gender"]?><br>
        </section>
    </main>
  </body>
</html>