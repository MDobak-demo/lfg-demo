<?php

namespace LFG\App\Router;

use LFG\App\Router\Exception\GivenClassDoNotExistsException;

/**
 * Class FallbackActionHandler
 *
 * Handles action written in old convention: "FullyQualifiedName@MethodName" eg. "LFG\Application@main"
 */
class FallbackActionHandler implements ActionHandlerInterface
{
    const RX_PATTERN = "#^[^@]+@.+$#";

    /**
     * {@inheritDoc}
     */
    public function supportAction(string $action): bool
    {
        return (int)preg_match(self::RX_PATTERN, $action) > 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getCallableForAction(string $path, string $action): callable
    {
        list($className, $method) = explode('@', $action, 2);

        if (!class_exists($className) || !method_exists($className, $method)) {
            throw GivenClassDoNotExistsException::create($action);
        }

        // Note: It works only on static classes beacuse i want to make code compatible with given App::run_action
        // method but it will be very easy to inject some dependency injection provider to this class.
        return function () use ($className, $method) { return call_user_func([$className, $method]); };
    }
}
