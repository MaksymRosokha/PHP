<?php

declare(strict_types=1);

if(empty($_GET["day"])) {
    exit();
}
?>

<!doctype html>
<html lang="uk">
<head>
    <title><?php echo basename( $_GET["day"], ".log"); ?></title>
    <meta charset="UTF-8">

    <style>
        h1{
            text-align: center;
        }

    </style>
</head>
<body>
<h1><?php echo basename( $_GET["day"], ".log"); ?></h1>

<ul>
    <?php echo nl2br(file_get_contents("logs/" . $_GET["day"] . ".log")); ?>
</ul>
</body>
</html>