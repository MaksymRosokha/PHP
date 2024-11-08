<?php

declare(strict_types=1);

ini_set("display_errors", "1");
error_reporting(E_ALL);

function debug(mixed $input): void
{
    echo "<pre>";
    var_dump($input);
    echo "</pre>";
    exit;
}

function pathBuilder(): string
{
    return join(DIRECTORY_SEPARATOR, func_get_args());
}
