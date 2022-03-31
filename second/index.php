<?php

session_start();
use app\Router;

define("DIR_MAIN", $_SERVER['DOCUMENT_ROOT']);
const DIR_APP = DIR_MAIN . '/app';
const DIR_VIEW = DIR_APP . '/view';
const DEBUG_MODE = true;

if (DEBUG_MODE) {
    ini_set("display_errors", 1);
    ini_set("error_reporting", E_ALL);
    ini_set("display_startup_errors", 1);
}


spl_autoload_register(function($class) {
    $fileClass = str_replace('\\', '/', $class) . '.php';

    if (file_exists($fileClass)) {
        require $fileClass;
    } else {
        die("We did not find file: {$fileClass}");
    }
});


Router::rout($_REQUEST['controller'] ?? 'Site', $_REQUEST['action'] ?? 'actionIndex');
