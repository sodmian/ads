<?php

namespace Core;

use Dotenv\Dotenv;

class Route implements IRoute
{
    private static $controller;
    private static $mode;
    private static $params;

    static function init()
    {
        $dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'], '.env');
        $dotenv->load();

        self::setInstance();

        //TODO: Сомнительная реализация, надо подумать...
        if (self::$controller == 'api') {
            $class = "\\Api\\" . self::camelize(self::$mode);
        } else {
            $class = "\\Controllers\\" . self::camelize(self::$controller);
        }

        if (class_exists($class)) {
            $controller = new $class;
            $mode = self::$mode;

            if (method_exists($controller, $mode)) {
                $controller->$mode();
            } else {
                self::ErrorPage404();
            }
        } else {
            self::ErrorPage404();
        }
    }

    static function setInstance()
    {
        $route = self::getRoute();

        if (!empty($route[0])) {
            self::$controller = $route[0];
        }

        if (!empty($route[1])) {
            self::$mode = $route[1];
        }

        if (!empty($_REQUEST)) {
            self::$params = $_REQUEST;
        }
    }

    static function camelize(string $string, $upper = true): array|string|null
    {
        $regexp = $upper ? '/(?:^|_)(.?)/' : '/_(.?)/';

        return preg_replace_callback($regexp, function ($matches) {
            return strtoupper($matches[1]);
        }, $string);
    }

    static function getInstance(): object
    {
        return (object)[
            'controller' => self::$controller,
            'mode' => self::$mode,
            'params' => self::$params
        ];
    }

    static function getRoute()
    {
        $url = parse_url($_SERVER['REQUEST_URI']);

        $path = str_replace('index.php', '', $url['path'] ?? '');
        $query = $url['query'] ?? '';

        preg_match('/route=([^&]*)/', $query, $route);

        $route = explode('.', $route[1] ?? 'home.view');

        //TODO: Пока не реализованы SEF-ссылки
        if ($path != '/' || !isset($route[1])) {
            self::ErrorPage404();
            exit;
        }

        return $route;
    }

    static function ErrorPage404()
    {
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
    }
}