<?php

declare(strict_types=1);

namespace Rosokha\App\ajax;

require $_SERVER['DOCUMENT_ROOT'] . '\vendor\autoload.php';

use Rosokha\App\dto\TaskDTO;
use Rosokha\App\validators\LogInFormValidator;
use Rosokha\DB\Factory\DAOFactoryImpl;

$errors = LogInFormValidator::validateData($_POST);
$_POST = [];
if (empty($errors)) {
    $factoryDAO = DAOFactoryImpl::getInstance();
    $taskDAO = $factoryDAO::getTaskDAO();
    $user = $_SESSION['user'];
    $tasksDAO = $taskDAO->getByUserId($user->getId());
    $tasks = [];
    foreach ($tasksDAO as $task) {
        $tasks[] = new TaskDTO($task);
    }

    require $_SERVER['DOCUMENT_ROOT'] . pathBuilder('', 'src', 'views', 'todo', 'todo.php');
} else {
    require $_SERVER['DOCUMENT_ROOT'] . pathBuilder('', 'src', 'views', 'errors', 'printErrors.php');
}
