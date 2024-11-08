<?php

declare(strict_types=1);

namespace Arakviel\App\Controllers;

use Arakviel\App\Core\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $user = $this->model->getUser();
        $users = $this->model->getUsers();

        $vars = [
            "firstName" => $user->getFirstName(),
            "lastName" => $user->getLastName(),
            "users" => $users,
        ];
        $this->view->render("Головна сторінка", $vars);
    }
}
