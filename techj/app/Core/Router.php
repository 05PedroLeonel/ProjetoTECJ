<?php
namespace App\Core;
class Router {
    private array $routes = ['GET'=>[],'POST'=>[]];
    public function get(string $path, string $action){ $this->routes['GET'][$path]=$action; }
    public function post(string $path, string $action){ $this->routes['POST'][$path]=$action; }
    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        // normalize
        $uri = '/' . trim($uri, '/');
        $action = $this->routes[$method][$uri] ?? null;
        if(!$action){ http_response_code(404); echo '404 Not Found'; return; }
        [$controller, $methodAction] = explode('@', $action);
        $controllerClass = "App\\Controllers\\{$controller}";
        if(!class_exists($controllerClass)){
            // try to require file
            $file = __DIR__ . "/../Controllers/{$controller}.php";
            if(file_exists($file)) require_once $file;
        }
        if(!class_exists($controllerClass)){
            throw new \RuntimeException("Controller {$controllerClass} not found");
        }
        $c = new $controllerClass();
        if(!method_exists($c, $methodAction)) throw new \RuntimeException("Method {$methodAction} not found in {$controllerClass}");
        $c->$methodAction();
    }
}
