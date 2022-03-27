<?php
namespace app;

use app\controller\Site;

class Router {
    public static function rout($controller, $action) {
        $controller = 'app\controller\\' . $controller;
        $notFound = true;

        if (class_exists($controller)) {
            if (method_exists($controller, $action)) {
                $c = new $controller();
                $c->$action();
                $notFound = false;
            } else {
                self::showError("Action '{$action}' was not found");
            }
        } else {
            self::showError("Controller '{$controller}' was not found");
        }

        if ($notFound) {
            $c = new Site();
            $c->actionNotFound();

        }
    }

    private static function showError($error) {
        if (DEBUG_MODE) {
            echo $error . '<br>';
        }
    }
}