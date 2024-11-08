<?php

declare(strict_types=1);

namespace Rosokha\App\controllers;

use Rosokha\App\core\Controller;

/**
 *
 */
class AccountController extends Controller
{

    /**
     * @param array $route
     */
    public function __construct(array $route)
    {
        parent::__construct($route);
    }

    /**
     * @return void
     */
    public function loginAction()
    {
        parent::getView()->render("Login page");
    }

    /**
     * @return void
     */
    public function registerAction()
    {
        parent::getView()->render("Register page");
    }

}