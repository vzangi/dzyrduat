<?php

trait SingletonTrait {
    private static $_instance;

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function __clone(){}
}