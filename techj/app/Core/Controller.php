<?php
namespace App\Core;
class Controller {
    protected function view(string $view, array $data=[]){
        extract($data);
        $viewPath = __DIR__ . "/../Views/{$view}.php";
        require __DIR__ . "/../Views/layout.php";
    }
    protected function redirect(string $path){ header('Location: ' . $path); exit; }
}
