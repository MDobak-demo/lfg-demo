<?php
class Route {
    private static $instance = null;

    private $routes  = array();
    private $actions = array();

    public static function get_instance()
    {
        if(!self::$instance){
            self::$instance = new Route();
        }
        return self::$instance;
    }

    public function __construct()
    {
    }

    private static function reorder_routes()
    {
        usort(self::get_instance()->routes, function($a, $b){
            return mb_strlen($a['route']) < mb_strlen($b['route']);
        });
    }

    public static function bind_function($route, $callback)
    {
        self::get_instance()->routes[] = array(
            'route'     => trim($route, '/'),
            'action'    => '',
            'query'     => '',
            'callback'  => $callback,
        );

        self::reorder_routes();
    }

    public static function bind_action($route, $action)
    {
        $Callback = function() use ($action)
        {
            return App::run_action($action);
        };

        self::get_instance()->routes[] = array(
            'route'     => trim($route, '/'),
            'action'    => $action,
            'query'     => '',
            'callback'  => $Callback,
        );

        self::get_instance()->actions[$action] = ltrim($route, '/');

        self::reorder_routes();
    }

    public static function find_route($query)
    {
        foreach(self::get_instance()->routes as $Item){
            $qr = preg_quote($Item['route']);

            if(preg_match('#^'. $qr .'(/.*?)?$#uis', ($qr == '' ? '/' : '') . $query, $Match)){
                $Item['query'] = isset($Match[1]) ? trim($Match[1], '/') : '';
                return $Item;
            }
        }
        return null;
    }

    public static function find_action_url($action)
    {
        if(isset(self::get_instance()->actions[$action])){
            return self::get_instance()->actions[$action];
        }

        return '';
    }

    public static function redirect($link = "")
    {
        if($link == ""){
            Header("Location: ". $_SERVER['REQUEST_URI']);
        }
        else {
            Header("Location: ". url($link));
        }
        exit();
    }
}
