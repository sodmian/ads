<?php

namespace Controllers;

class Log extends \Core\Presenter
{
    public static array $errors = [];

    public static function add(string $str): string
    {
        return self::$errors[] = $str;
    }
}