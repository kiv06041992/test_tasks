<?php
namespace app;

class DB {
    private static $c;
    public static function getConnection() {
        if (!self::$c) {
            self::$c = mysqli_connect('localhost', 'base', 'base', 'exam');
        }
        return self::$c;
    }

}