<?php
namespace Gpupo\CommonSdk\Traits;

trait MagicCallTrait
{
    /**
     * Magic method that implements
     *
     * @param string $method
     * @param array  $args
     *
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function __call($method, $args)
    {
        $command = substr($method, 0, 3);
        $field = substr($method, 3);
        $field[0] = strtolower($field[0]);

        if ($command == "set") {
            $this->set($field, current($args));

            return $this;
        } elseif ($command == "get") {
            return $this->get($field);
        } elseif ($command == "add") {
            $this->add($field, current($args));

            return $this;
        } else {
            throw new \BadMethodCallException("There is no method ".$method);
        }
    }
}
