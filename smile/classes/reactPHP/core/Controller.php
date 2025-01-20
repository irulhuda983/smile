<?php
// core/Controller.php
namespace Core;

require_once __DIR__ . '/../app/vendor/autoload.php';
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        require dirname(__DIR__) . '/app/views/' . $view . '.php';
    }
}
