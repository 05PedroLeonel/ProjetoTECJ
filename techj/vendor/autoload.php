<?php
spl_autoload_register(function($class){
    $prefix = 'App\\';
    if(strpos($class, $prefix) === 0){
        $relative = substr($class, strlen($prefix));
        $file = __DIR__ . '/../app/' . str_replace('\\\\', '/', $relative) . '.php';
        $file = str_replace('App/', 'app/', $file);
        if(file_exists($file)) require $file;
    }
});
