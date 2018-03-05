<?php

namespace LFG\App\Router;

use LFG\App\App;

/**
 * Class Route
 */
class Router
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * @var array
     */
    private $actions = [];

    /**
     * @param string   $route
     * @param callable $callback
     */
    public function bindFunction(string $route, callable $callback)
    {
        $this->routes[] = [
            'route'    => trim($route, '/'),
            'action'   => '',
            'query'    => '',
            'callback' => $callback,
        ];

        self::reorderRoutes();
    }

    /**
     * @param string $route
     * @param string $action
     */
    public function bindAction(string $route, string $action)
    {
        $Callback = function () use ($action) {
            return App::run_action($action);
        };

        $this->routes[] = [
            'route'    => trim($route, '/'),
            'action'   => $action,
            'query'    => '',
            'callback' => $Callback,
        ];

        $this->actions[$action] = ltrim($route, '/');

        self::reorderRoutes();
    }

    /**
     * @param string $query
     *
     * @return mixed|null
     */
    public function findRoute(string $query)
    {
        foreach ($this->routes as $Item) {
            $qr = preg_quote($Item['route']);

            if (preg_match('#^'.$qr.'(/.*?)?$#uis', ($qr == '' ? '/' : '').$query, $Match)) {
                $Item['query'] = isset($Match[1]) ? trim($Match[1], '/') : '';

                return $Item;
            }
        }

        return null;
    }

    /**
     * @param string $action
     *
     * @return mixed|string
     */
    public function findActionUrl(string $action)
    {
        if (isset($this->actions[$action])) {
            return $this->actions[$action];
        }

        return '';
    }

    /**
     * @param string $link
     */
    public function redirect(string $link = "")
    {
        if ($link == "") {
            Header("Location: ".$_SERVER['REQUEST_URI']);
        } else {
            Header("Location: ".url($link));
        }
        exit();
    }

    private function reorderRoutes()
    {
        usort(
            $this->routes,
            function ($a, $b) {
                return mb_strlen($a['route']) < mb_strlen($b['route']);
            }
        );
    }
}
