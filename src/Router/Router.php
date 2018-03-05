<?php

namespace LFG\App\Router;

use LFG\App\App;
use LFG\App\Router\Exception\UnsupportedActionException;

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
     * @var ActionHandlerInterface[]
     */
    private $actionHandlers = [];

    /**
     * @param ActionHandlerInterface $actionHandler
     */
    public function registerActionHandler(ActionHandlerInterface $actionHandler)
    {
        $this->actionHandlers[] = $actionHandler;
    }

    /**
     * @param string   $path
     * @param callable $callback
     */
    public function bindFunction(string $path, callable $callback)
    {
        // Note: It would good idea to consider, if this types of routes should also use action as name.
        $this->routes[] = new Route($path, '', $callback);
        $this->routesOrdered = false;
    }

    /**
     * @param string $path
     * @param string $action
     *
     * @throws UnsupportedActionException
     */
    public function bindAction(string $path, string $action)
    {
        $callback = null;

        foreach ($this->actionHandlers as $handler) {
            if ($handler->supportAction($action)) {
                $callback = $handler->getCallableForAction($path, $action);
            }
        }

        if (null === $callback) {
            throw UnsupportedActionException::create($action);
        }

        $this->routes[] = new Route($path, $action, $callback);
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

            // Note: I really don't like this solution. It is slow and very primitive but i decided to keep previous logic to
            // to make Router a much compatible as possible with our hypothetical application.
            // BTW: Good article about writing fast regexp based router: http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html
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
     * @todo: It will be better to return null instead of empty string if route is not founded. In real world case i
     *        i would decide to not change this logic because benefits are too low.
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
