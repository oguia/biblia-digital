<?php

class Router {
    protected $routes = [];

    public function add($method, $route, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function get($route, $controller, $action) {
        $this->add('GET', $route, $controller, $action);
    }

    public function post($route, $controller, $action) {
        $this->add('POST', $route, $controller, $action);
    }

    public function dispatch($uri, $method) {
        // Remove query string
        $uri = strtok($uri, '?');

        // Remove trailing slash except for root
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['route']);
            $pattern = "@^" . $pattern . "$@";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match

                $controllerName = $route['controller'];
                $actionName = $route['action'];

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $actionName)) {
                        call_user_func_array([$controller, $actionName], $matches);
                        return;
                    }
                }
            }
        }

        // 404
        http_response_code(404);
        echo "404 Not Found";
    }
}
