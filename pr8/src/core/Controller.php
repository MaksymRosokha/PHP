<?php

declare(strict_types=1);

namespace Rosokha\App\core;

/**
 *
 */
abstract class Controller
{
    /**
     * @var array
     */
    public array $acl;
    /**
     * @var array
     */
    private array $route;
    /**
     * @var View
     */
    private View $view;
    /**
     * @var Model
     */
    private Model $model;

    /**
     * @param array $route
     */
    public function __construct(array $route)
    {
        $this->route = $route;
        if (!$this->checkACL()) {
            View::errorCode(403);
        }
        $this->view = new View($this->route);
        $this->model = $this->loadModel($route['controller']);
    }

    /**
     * Check Access Control List
     * @return bool - Access Control
     */
    public function checkACL(): bool
    {
        $pathToACL = 'src/acl/' . $this->route['controller'] . '.php';
        if (file_exists($pathToACL)) {
            $this->acl = require $pathToACL;
            if ($this->isACL('all')) {
                return true;
            } elseif (isset($_SESSION['authorize']['id']) && $this->isACL('authorize')) {
                return true;
            }
        } else {
            View::errorCode(404);
        }
        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isACL(string $key): bool
    {
        return in_array($this->route['action'], $this->acl[$key]);
    }

    /**
     * @param string $name
     * @return Model
     */
    public function loadModel(string $name): Model
    {
        $model = pathBuilder('Rosokha', 'App', 'models', ucfirst($name));
        if (class_exists($model)) {
            return new $model();
        }
        View::errorCode(404);
        exit();
    }

    /**
     * @return array
     */
    public function getRoute(): array
    {
        return $this->route;
    }

    /**
     * @param array $route
     */
    public function setRoute(array $route): void
    {
        $this->route = $route;
    }

    /**
     * @return View
     */
    public function getView(): View
    {
        return $this->view;
    }

    /**
     * @param View $view
     */
    public function setView(View $view): void
    {
        $this->view = $view;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

}
