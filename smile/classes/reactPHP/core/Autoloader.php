<?php
// core/Autoloader.php

class Autoloader {
    public static function register() {
        spl_autoload_register(function($class) {
            $file = dirname(__DIR__) . '/' . str_replace('\\', '/', $class) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        });
    }
}

Autoloader::register();
