<?php

namespace Waze;

class Singleton
{
    protected static $instance;

    /**
     *
     * @param array $params
     *
     * @return $this
     */
    public static function getInstance($params = [])
    {
        if (!is_array(static::$instance)) {
            static::$instance = [];
        }
        $class = get_called_class();
        if (!isset(static::$instance[$class])) {
            $instance = new $class();
            static::$instance[$class] = $instance;
            if (method_exists($instance, 'init')) {
                $instance->init($params);
            }
        }
        return static::$instance[$class];
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }
}