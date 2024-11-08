<?php

declare(strict_types=1);

namespace Arakviel\App\Core;

class View
{
    public string $path;
    protected array $route;
    public string $layout = "default";

    /**
     * @param $route - from Router class
     */
    public function __construct($route)
    {
        $this->route = $route;
        $this->path = pathBuilder(ucfirst($route["controller"]), $route["action"]);
    }

    /**
     * render view by $route
     * @param string $title - tittle name of site
     * @param array $vars - custom vars to view
     */
    public function render(string $title, array $vars = []): void
    {
        extract($vars);
        $viewFile = pathBuilder("App", "Views", "{$this->path}.php");
        if (file_exists($viewFile)) {
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            require pathBuilder("App", "Views", "layouts", "{$this->layout}.php");
        }
    }

    public function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    public static function errorCode($code): void
    {
        http_response_code($code);
        $errorFile = pathBuilder("App", "Views", "errors", "{$code}.php");
        if (file_exists($errorFile)) {
            require $errorFile;
        }
        exit;
    }
}
