<?php

declare(strict_types=1);
//Росоха Максим Валентинович
/*
 * Дата виконання:
 * 13.09.2022
 * */

define("TABLE_NAME", "animals");
$create = <<<EOT
    <pre>
    <code>
    INSERT INTO %s (id, name, type, age, is_swim, is_fly)
    VALUES ("Спайк", "Собака", 5, 1, 0),
           ("Мурчик", "Кіт", 2, 0, 0),
           ("Скрудж", "Качка", 1, 1, 1);
    </code>
    </pre>
EOT;
$create = sprintf($create, TABLE_NAME);
echo $create;

$read = <<<EOT
    <pre>
    <code>
    SELECT *
      FROM %s
     WHERE is_swim = 1
        OR is_fly = 1;
    </code>
    </pre>
EOT;
$read = sprintf($read, TABLE_NAME);
echo @$read;

$update = <<<EOT
    <pre>
    <code>
    UPDATE %s
       SET age = 2
     WHERE id = 3;
    </code>
    </pre>
EOT;
$update = sprintf($update, TABLE_NAME);
echo $update;

$delete = <<<EOT
    <pre>
    <code>
    DELETE FROM %s
     WHERE id = 2;
    </code>
    </pre>
EOT;
$delete = sprintf($delete, TABLE_NAME);
echo $delete;