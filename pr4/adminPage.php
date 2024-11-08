<?php

declare(strict_types=1);
session_start();
?>

<!doctype html>
<html lang="uk">
<head>
    <title>Сторінка адміністратора</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="images/icons/Icon.ico">

    <style>
        h1{
            text-align: center;
        }
        header{
            background: #01a6a1;
        }
    </style>
</head>
<body>
    <header>
        <h1>Сторінка адміністратора</h1>
        <hr>
    </header>
    <main>
        <section id="admin-panel">
            <h4>Адмін-панель</h4>
            <a href="/logs.php" target="_blank">Переглянути візити сайту</a>
            <br><br>
            Бажаєте додати новий вид риб України?
            <form action="/upload.php" method="post" enctype = "multipart/form-data">
                <input type="file" name="newFish"><br>
                <input type="submit">
            </form>
            <br>
        </section>
        <hr>
    </main>
</body>
</html>