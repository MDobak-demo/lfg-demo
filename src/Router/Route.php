<?php

namespace LFG\App\Router;

/**
 * Class Route
 */
class Route
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $action;

    /**
     * @var callable
     */
    private $callback;

    /**
     * Route constructor.
     *
     * @param string   $path
     * @param string   $action
     * @param callable $callback
     */
    public function __construct($path, $action, callable $callback)
    {
        $this->path     = trim($path, '/');
        $this->action   = $action;
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }
}
