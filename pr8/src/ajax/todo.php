<?php

declare(strict_types=1);

namespace Rosokha\App\ajax;

use Rosokha\DB\entity\Task;
use Rosokha\DB\Factory\DAOFactoryImpl;

require $_SERVER['DOCUMENT_ROOT'] . '\vendor\autoload.php';
session_start();

if (!empty($_POST['contentCreateTask'])) {
    $factoryDAO = DAOFactoryImpl::getInstance();
    $taskDAO = $factoryDAO::getTaskDAO();

    $entity = new Task();
    $entity->setIdUser($_SESSION['user']->getId());
    $entity->setContent($_POST['contentCreateTask']);
    $entity->setStatus("todo");

    $taskDAO->insert($entity);
    $_POST['contentCreateTask'] = null;
}
if (!empty($_POST['idDeleteTask'])) {
    $factoryDAO = DAOFactoryImpl::getInstance();
    $taskDAO = $factoryDAO::getTaskDAO();
    $taskDAO->delete(intval($_POST['idDeleteTask']));
}