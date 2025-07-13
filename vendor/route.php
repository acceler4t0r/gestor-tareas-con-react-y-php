<?php
    namespace Vendor;

class Route {
    protected static $routes = [];

    public static function get($uri, $callback, $middleware = []) {
        self::addRoute('GET', $uri, $callback, $middleware);
    }

    public static function post($uri, $callback, $middleware = []) {
        self::addRoute('POST', $uri, $callback, $middleware);
    }

    public static function put($uri, $callback, $middleware = []) {
        self::addRoute('PUT', $uri, $callback, $middleware);
    }

    public static function delete($uri, $callback, $middleware = []) {
        self::addRoute('DELETE', $uri, $callback, $middleware);
    }


    protected static function addRoute($method, $uri, $callback, $middleware) {
        self::$routes[] = compact('method', 'uri', 'callback', 'middleware');
    }

    public static function handleRequest() {
        try {
            $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $currentUri = str_replace('/brangus/public', '', $currentUri);
            $currentUri = ($currentUri === "/") ? "/" : rtrim($currentUri, '/');
            $currentMethod = $_SERVER['REQUEST_METHOD'];

            foreach (self::$routes as $route) {
                $pattern = preg_replace('/\{(\w+)\}/', '([^\/]+)', $route['uri']);
                $pattern = "@^" . rtrim($pattern, '/') . "$@";

                if ($route['method'] === $currentMethod && preg_match($pattern, $currentUri, $matches)) {
                    array_shift($matches);

                    foreach ($route['middleware'] as $middleware) {
                        if (class_exists($middleware)) {
                            $middlewareInstance = new $middleware();
                            $middlewareInstance->handle();
                        }
                    }

                    if (is_callable($route['callback'])) {
                        return call_user_func_array($route['callback'], $matches);
                    }

                    if (is_array($route['callback']) &&
                        class_exists($route['callback'][0]) &&
                        method_exists($route['callback'][0], $route['callback'][1])) {

                        $controller = new $route['callback'][0]();
                        $action = $route['callback'][1];
                        return call_user_func_array([$controller, $action], $matches);
                    }

                    return self::sendJsonError(400, 'Invalid callback');
                }
            }

            return self::sendJsonError(404, 'Route not found');

        } catch (\Exception $e) {
            error_log($e->getMessage());
            return self::sendJsonError(500, 'Internal server error');
        }
    }


    protected static function sendJsonError(int $code, string $message) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'status' => $code,
            'message' => $message
        ]);
        exit;
    }
}


?>