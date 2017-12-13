<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 26.10.17
 * Time: 19:57
 */

namespace App\Core\Utilities;


class RouteLister
{
    public static function all()
    {
        $routes = collect();

        $router = app('router');

        foreach ($router->getRoutes() as $route) {
            if (is_null($route->getName())) {
                continue;
            }

            $routes->put($route->getName(), [
                'host'   => $route->domain(),
                'method' => implode('|', $route->methods()),
                'uri'    => $route->uri(),
                'name'   => $route->getName(),
            ]);

        }

        return $routes->toJson();
    }
}