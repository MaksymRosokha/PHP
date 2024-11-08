<?php

declare(strict_types=1);

namespace Rosokha\App\controllers;

use Rosokha\App\core\Controller;

/**
 *
 */
class MainController extends Controller
{
    /**
     * @return void
     */
    public function indexAction()
    {
        parent::getView()->render("To-Do-List");
    }
}