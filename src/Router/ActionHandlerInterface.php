<?php

namespace LFG\App\Router;

/**
 * Interface ActionHandlerInterface
 */
interface ActionHandlerInterface
{
    /**
     * Returns true if given action is supported by this handler. False otherwise.
     *
     * @param string $action
     *
     * @return bool
     */
    public function supportAction(string $action): bool;

    /**
     * Returns callable for given action.
     *
     * @param string $path
     * @param string $action
     *
     * @return callable
     */
    public function getCallableForAction(string $path, string $action): callable;
}
