<?php

namespace LFG\App\Router;

/**
 * Class MatchedRoute
 */
class MatchedRoute
{
    /**
     * @var Route
     */
    private $route;

    /**
     * @var string
     */
    private $query;

    /**
     * Route constructor.
     *
     * @param Route  $route
     * @param string $query
     */
    public function __construct(Route $route, $query)
    {
        $this->route = $route;
        $this->query = $query;
    }

    /**
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }
}
