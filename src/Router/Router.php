<?php

namespace LFG\App\Router;

use LFG\App\App;

/**
 * Class Route
 */
class Router
{
    /**
     * @var Route[]
     */
    private $routes = [];

    /**
     * @var bool
     */
    private $routesOrdered = true;

    /**
     * @param string   $route
     * @param callable $callback
     */
    public function bindFunction(string $route, callable $callback)
    {
        $this->routes[] = new Route($route, '', $callback);
        $this->routesOrdered = false;
    }

    /**
     * @param string $route
     * @param string $action
     */
    public function bindAction(string $route, string $action)
    {
        $callback = function () use ($action) {
            return App::run_action($action);
        };

        $this->routes[] = new Route($route, $action, $callback);
        $this->routesOrdered = false;
    }

    /**
     * @param string $query
     *
     * @return MatchedRoute|null
     */
    public function findRoute(string $query): ?MatchedRoute
    {
        self::reorderRoutes();

        foreach ($this->routes as $route) {
            $qr = preg_quote($route->getPath());

            if (preg_match('#^'.$qr.'(/.*?)?$#uis', ($qr == '' ? '/' : '').$query, $match)) {
                $query = isset($match[1]) ? trim($match[1], '/') : '';

                return new MatchedRoute($route, $query);
            }
        }

        return null;
    }

    /**
     * @param string $action
     *
     * @return string
     *
     * @todo: It will be better to return null instead of empty string if route is not founded
     */
    public function findActionUrl(string $action): string
    {
        foreach ($this->routes as $route) {
            if ($route->getAction() === $action && $route->getAction()) {
                return $route->getPath();
            }
        }

        return '';
    }

    private function reorderRoutes()
    {
        if ($this->routesOrdered) {
            return;
        }

        usort(
            $this->routes,
            function (Route $a, Route $b) {
                return mb_strlen($a->getPath()) < mb_strlen($b->getPath());
            }
        );
    }
}
