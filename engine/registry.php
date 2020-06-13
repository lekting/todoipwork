<?php

/*
* Registry class, registering some fields for classes which etends controllers
*/
class registry {

    protected $registry = [];

    public function get($key) {
        return ($this->has($key) ? $this->registry[$key] : null);
    }

    public function set($key, $value) {
        $this->registry[$key] = $value;
    }

    public function has($key) {
        return isset($this->registry[$key]);
    }

}