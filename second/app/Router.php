<?php
namespace app;

use app\controller\Site;

class Router {
    public static function rout(string $controller, string $action): void {
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

    private static function showError(string $error) {
        if (DEBUG_MODE) {
            echo $error . '<br>';
        }
    }
}