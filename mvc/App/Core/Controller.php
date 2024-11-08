<?php

declare(strict_types=1);

namespace Arakviel\App\Core;

abstract class Controller
{
    protected array $route;
    public View $view;
    public mixed $model;
    /**
     * @var array Access Control List
     */
    public array $ACL;

    /**
     * @param array $route - from Router class
     */
    public function __construct(array $route)
    {
        $this->route = $route;
        if (!$this->checkACL()) {
            View::errorCode(403);
        }
        $this->view = new View($route);
        $this->model = $this->loadModel($route["controller"]);
    }

    public function loadModel($name)
    {
        $model = pathBuilder("Arakviel", "App", "Models", ucfirst($name));
        if (class_exists($model)) {
            return new $model();
        }
        return null;
    }

    /**
     * Check Access Control List
     * @return bool - Access Control
     */
    public function checkACL(): bool
    {
        $this->ACL = require 'App/ACL/' . $this->route['controller'] . '.php';
        if ($this->isACL('all')) {
            return true;
        } elseif (isset($_SESSION['authorize']['id']) and $this->isACL('authorize')) {
            return true;
        } elseif (!isset($_SESSION['authorize']['id']) and $this->isACL('guest')) {
            return true;
        } elseif (isset($_SESSION['admin']) and $this->isACL('admin')) {
            return true;
        }
        return false;
    }

    public function isACL($key): bool
    {
        return in_array($this->route['action'], $this->ACL[$key]);
    }
}
