<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public function direct($uri, $method)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = trim($uri, '/');

        // If empty, it's home
        if ($uri === '') {
            $uri = '';
        }

        if (array_key_exists($uri, $this->routes[$method])) {

            return $this->callAction(
                ...explode('@', $this->routes[$method][$uri])
            );
        }

        exit('404 Page Not Found');
    }

    protected function callAction($controller, $action)
    {
        $controller = "App\\Controllers\\{$controller}";

        // We need to verify if the class exists, but for simple autoloader we just new it.
        // Since we don't have a sophisticated autoloader, we might need to require it manually
        // or register an autoloader in index.php

        $controllerInstance = new $controller;

        if (! method_exists($controllerInstance, $action)) {
            exit(
                "{$controller} does not respond to the {$action} action."
            );
        }

        return $controllerInstance->$action();
    }

    public static function load($file)
    {
        $router = new static;
        require $file;

        return $router;
    }
}
