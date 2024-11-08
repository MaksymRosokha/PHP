<?php

declare(strict_types=1);

namespace Arakviel\App\Core;

class Router
{
    protected array $routes = [];
    protected array $params = [];

    public function __construct()
    {
        foreach (ROUTES as $route => $params) {
            $this->add($route, $params);
        }
    }

    public function add(string $route, array $params): void
    {
        $route = "#^" . $route . "$#";
        $this->routes[$route] = $params;
    }

    public function match(): bool
    {
        $url = trim($_SERVER["REQUEST_URI"], '/');
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function run(): void
    {
        if ($this->match()) {
            $controller = pathBuilder(
                "Arakviel",
                "App",
                "Controllers",
                ucfirst($this->params["controller"]) . "Controller"
            );
            if (class_exists($controller)) {
                $action = $this->params["action"] . "Action";
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
}
