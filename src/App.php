<?php

namespace LFG\App;

/**
 * Class App Mock
 *
 * @author Michal Dobaczewski <mdobak@gmail.com>
 */
class App
{
    /**
     * @param string $action
     *
     * @deprecated Moved to Router/FallbackActionHandler
     *
     * @return string
     */
    public static function run_action(string $action)
    {
        return $action;
    }
}
