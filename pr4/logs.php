<?php

declare(strict_types=1);

define("PATH_TO_LOGS", "logs");

$visits = array_diff(scandir(PATH_TO_LOGS), array('..', '.'));

?>

<!doctype html>
<html lang="uk">
    <head>
        <title>Візити сайту</title>
        <meta charset="UTF-8">

        <style>
            h1{
                text-align: center;
            }
            li{
                font-size: 150%;
            }

            input[type="text"] {
                border: unset;
            }
        </style>
    </head>
    <body>
        <h1>Візити сайту</h1>

        <ul>
        <?php
            foreach ($visits as $visit){ ?>
                <li>
                    <form action="/visits.php" method="get">
                        <input type="text" value="<?php echo basename($visit, ".log"); ?>" name="day"
                               readonly size="auto">
                        <input type="submit" value="Подивитись">
                    </form>
                </li><?php
            } ?>
        </ul>
    </body>
</html>
