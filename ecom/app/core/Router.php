<?php

class Router
{
    private $routes = [];

    // Register a GET route
    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    // Register a POST route
    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    // Dispatch the request to the correct handler
    public function dispatch($method, $uri)
    {
        $method = strtoupper($method);
        $uri = strtok($uri, '?'); // Remove query parameters

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];

            // If the handler is a closure, call it directly
            if (is_callable($handler)) {
                return call_user_func($handler);
            }

            // If it's a controller method (e.g., "HomeController@index")
            if (is_string($handler)) {
                list($controller, $method) = explode('@', $handler);

                require_once __DIR__ . "/../controllers/$controller.php";
                $controllerInstance = new $controller();
                return call_user_func([$controllerInstance, $method]);
            }
        }

        // If no matching route is found, return a 404 response
        http_response_code(404);
        require_once __DIR__ . '/../views/404.php';
    }
}
