<?php

namespace LFG\App\Router\Exception;

/**
 * Class GivenClassDoNotExistsException
 *
 * @author Michal Dobaczewski <mdobak@gmail.com>
 */
class GivenClassDoNotExistsException extends RouterException
{
    /**
     * @param $action
     *
     * @return GivenClassDoNotExistsException
     */
    public static function create($action)
    {
        return new self(sprintf("Class for action %s do not exists!", $action));
    }
}
