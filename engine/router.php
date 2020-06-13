<?php

/*
* Класс роутинга
*/
class router {

    private $routes = [];

    public function __construct() {
        $this->set('main', 'main');
        $this->set('task/add', 'task@add');
        $this->set('task/mark', 'task@mark');
        $this->set('task/get', 'task@get');
        $this->set('task/edit', 'task@edit');
        $this->set('login', 'login@login');
        $this->set('logout', 'login@logout');
    }

    public function get($key) {
        return ($this->has($key) ? $this->rotes[$key] : null);
    }

    public function set($key, $value) {
        $this->rotes[$key] = $value;
    }

    public function has($key) {
        return isset($this->rotes[$key]);
    }

}