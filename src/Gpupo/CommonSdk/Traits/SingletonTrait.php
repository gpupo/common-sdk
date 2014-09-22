<?php
namespace Gpupo\CommonSdk\Traits;

trait SingletonTrait
{
    protected static $instance;

    /**
     * Permite acesso a instancia dinamica
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $class=get_called_class();
            self::$instance = new $class();
        }

        return self::$instance;
    }
}
