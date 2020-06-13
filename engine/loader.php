<?php

/*
* Loader class. Need for loading controllers and the files
*/
class loader {

    private $registry;

    public function __construct($registry) {
        $this->registry = $registry;
    }

    /*
    * Getting view and returning content
    */
    public function view($view, $data = []) {
        if(is_file(DIR_APPLICATION."views/{$view}.php")) {
            ob_start();

            include_once DIR_APPLICATION."views/{$view}.php";

            $output = ob_get_clean();
            
            return $output;
        }

        return false;
    }

    /*
    * Getting controller and returning the result
    */
    public function controller($action, $data = [], $needroute = false) {
        $action_arr = str_split($action);

        if(end($action_arr) === '/')
            array_splice($action_arr, count($action_arr) - 1, 1);

        $action = implode($action_arr);

        $route = $needroute ? $this->registry->get('router')->get($action) : $action;

        if(strstr($route, '@')) {
            $splitted = explode('@', $route);
            $route = $splitted[0];
            $func = $splitted[1];
        }

        if(is_file(DIR_APPLICATION."controller/{$route}.php")) {
            if($needroute && !$route)
                return false;

            include_once DIR_APPLICATION."controller/{$route}.php";

            if(strstr($route, '/'))
                $route = explode('/', $route)[1];

            $fir = ucfirst($route);
            $class = "Controller{$fir}";

            $controller = new $class($this->registry);

            $func = $func ?? 'index';

            $output = $controller->$func([&$data]);

            return $output;
        }

        return false;
    }

}