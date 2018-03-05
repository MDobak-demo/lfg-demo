<?php

namespace LFG\App\Router\Exception;

/**
 * Class UnsupportedActionException
 *
 * @author Michal Dobaczewski <mdobak@gmail.com>
 */
class UnsupportedActionException extends RouterException
{
    /**
     * @return UnsupportedActionException
     */
    public static function create($action)
    {
        return new self(sprintf("There is no handler which can support %s action!", $action));
    }
}
