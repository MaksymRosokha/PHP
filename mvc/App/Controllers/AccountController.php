<?php

declare(strict_types=1);

namespace Arakviel\App\Controllers;

use Arakviel\App\Core\Controller;

class AccountController extends Controller
{
    //Тут можна валідувати дані, обезопасити сайт від атак і т.д.
    public function loginAction()
    {
        $this->view->render("Авторизація");
    }

    public function registerAction()
    {
        $this->view->render("Реєстрація");
    }
}
