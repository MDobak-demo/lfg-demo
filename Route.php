<?php

class Route {
    private $routes  = array();
    private $actions = array();

    public function __construct()
    {
    }

    private function reorder_routes()
    {
        usort($this->routes, function($a, $b){
            return mb_strlen($a['route']) < mb_strlen($b['route']);
        });
    }

    public function bind_function($route, $callback)
    {
        $this->routes[] = array(
            'route'     => trim($route, '/'),
            'action'    => '',
            'query'     => '',
            'callback'  => $callback,
        );

        self::reorder_routes();
    }

    public function bind_action($route, $action)
    {
        $Callback = function() use ($action)
        {
            return App::run_action($action);
        };

        $this->routes[] = array(
            'route'     => trim($route, '/'),
            'action'    => $action,
            'query'     => '',
            'callback'  => $Callback,
        );

        $this->actions[$action] = ltrim($route, '/');

        self::reorder_routes();
    }

    public function find_route($query)
    {
        foreach($this->routes as $Item){
            $qr = preg_quote($Item['route']);

            if(preg_match('#^'. $qr .'(/.*?)?$#uis', ($qr == '' ? '/' : '') . $query, $Match)){
                $Item['query'] = isset($Match[1]) ? trim($Match[1], '/') : '';
                return $Item;
            }
        }
        return null;
    }

    public function find_action_url($action)
    {
        if(isset($this->actions[$action])){
            return $this->actions[$action];
        }

        return '';
    }

    public function redirect($link = "")
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
