<?php

namespace Helpers;

class RouteHelper
{
    public static function getRequestUri()
    {
        $dirname = dirname($_SERVER["SCRIPT_NAME"]);
        $dirname = $dirname != "/" ? $dirname : null;
        $basename = basename($_SERVER["SCRIPT_NAME"]);
        return str_replace([$dirname, $basename], null, $_SERVER["REQUEST_URI"]);
    }

    public static function addRoute($url, $callback)
    {
        $patterns = ["{url}" => "([0-9a-zA-Z]+)", "{id}" => "([0-9]+)"];
        $url = str_replace(array_keys($patterns), array_values($patterns), $url);
        $requestUri = self::getRequestUri();
        if (preg_match("@^" . $url . "$@", $requestUri, $parameters)) {
            unset($parameters[0]);
            if (is_callable($callback)) {
                call_user_func_array($callback, $parameters);
            } else {
                $path = explode("@", $callback);
                if (file_exists("../Application/Controllers/{$path[0]}.php")) {
                    $controller = str_replace("/", "\\", implode("/", explode("/", $path[0])));
                    $controller = "\Controllers\\{$controller}";
                    call_user_func_array([new $controller, $path[1]], $parameters);
                }
            }
        }
    }
}
