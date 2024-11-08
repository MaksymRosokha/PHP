<?php

declare(strict_types=1);
session_start();

$_SESSION['userName'] = null;
$_SESSION['password'] = null;
$_SESSION['avatar'] = null;
$_SESSION['group'] = null;
$_SESSION['email'] = null;
$_SESSION['phoneNumber'] = null;
$_SESSION['gender'] = null;

setcookie("userName", "", time() - 1000, "/");
setcookie("password", "", time() - 1000, "/");
setcookie("isRememberMe", "No", time() - 1000, "/");

header( "Location: Index.php" );