<?php

declare(strict_types=1);

namespace Rosokha\App\core;

/**
 *
 */
class View
{
    /**
     * @var string
     */
    private string $path;
    /**
     * @var array
     */
    private array $route;
    /**
     * @var string
     */
    private string $layout = "default";

    /**
     * @param array $route
     */
    public function __construct(array $route)
    {
        $this->route = $route;
        $this->path = $route['controller'] . '/' . $route['action'];
    }

    /**
     * @param string $title
     * @param array $vars
     * @return void
     */
    public function render(string $title, array $vars = [])
    {
        extract($vars);
        if (file_exists(pathBuilder("src", "views", $this->path . '.php'))) {
            ob_start();
            require pathBuilder("src", "views", $this->path . '.php');
            $content = ob_get_clean();
            require pathBuilder("src", "views", "layouts", $this->layout . '.php');
        } else {
            self::errorCode(404);
        }
    }

    /**
     * @param $code
     * @return void
     */
    public static function errorCode($code)
    {
        http_response_code($code);
        $errorFile = pathBuilder("src", "views", "errors", $code . '.php');
        if (file_exists($errorFile)) {
            require $errorFile;
        } else {
            die("Page of the error is absent");
        }
        exit();
    }

    /**
     * @param $url
     * @return void
     */
    public function redirect($url)
    {
        header('location: ' . $url);
        exit();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

}