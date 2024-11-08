<?php

declare(strict_types=1);

use Rosokha\App\dto\TaskDTO;
use Rosokha\App\validators\SignUpFormValidator;
use Rosokha\DB\Factory\DAOFactoryImpl;

require $_SERVER['DOCUMENT_ROOT'] . '\vendor\autoload.php';

$errors = SignUpFormValidator::validateData($_POST);
$_POST = [];
if (empty($errors)) {
    $factoryDAO = DAOFactoryImpl::getInstance();
    $taskDAO = $factoryDAO::getTaskDAO();
    $user = $_SESSION['user'];
    $tasksDAO = $taskDAO->getByUserId($user->getId(), "todo");
    $tasks = [];
    foreach ($tasksDAO as $task) {
        $tasks[] = new TaskDTO($task);
    }
    require $_SERVER['DOCUMENT_ROOT'] . pathBuilder('', 'src', 'views', 'todo', 'todo.php');
} else {
    require $_SERVER['DOCUMENT_ROOT'] . pathBuilder('', 'src', 'views', 'errors', 'printErrors.php');
}