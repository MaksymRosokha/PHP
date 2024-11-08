<?php

declare(strict_types=1);

namespace Rosokha\App\core;

/**
 *
 */
class Router
{

    /**
     * @var array
     */
    protected array $routes = [];
    /**
     * @var array
     */
    protected array $params = [];

    /**
     *
     */
    public function __construct()
    {
        foreach (ROUTES as $route => $params) {
            $this->add($route, $params);
        }
    }

    /**
     * @param $route
     * @param $params
     * @return void
     */
    public function add($route, $params): void
    {
        $route = '#^' . $route . '$#';
        $this->routes[$route] = $params;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        if ($this->match()) {
            $controller = pathBuilder(
                "Rosokha",
                "App",
                "controllers",
                ucfirst($this->params['controller']) . "Controller"
            );
            if (class_exists($controller)) {
                $action = $this->params['action'] . 'Action';
                if (method_exists($controller, $action)) {
                    $currentController = new $controller($this->params);
                    $currentController->$action();
                } else {
                    View::errorCode(404);
                }
            } else {
                View::errorCode(404);
            }
        } else {
            View::errorCode(404);
        }
    }

    /**
     * @return bool
     */
    public function match(): bool
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

}