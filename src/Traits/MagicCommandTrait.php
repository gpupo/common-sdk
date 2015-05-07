<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\CommonSdk\Traits;

/**
 * Rápida implementação de métodos mágicos.
 *
 * Exemplo de uso :
 *
 * <code>
 *
 *    use MagicCommandTrait;
 *
 * 	  //...
 *    $this->magicCommandCallAdd('create');
 *    //...
 *
 *    protected function magicCreate($suplement, $input)
 *    {
 *        // faça alguma coisa ao acesso de $self->creatSuplement($input)
 *    }
 *
 * </code>
 */
trait MagicCommandTrait
{
    public function getSchema()
    {
        return [];
    }

    protected $magicCommands = [];

    /**
     *  @return array
     */
    protected function magicCommandCallList()
    {
        return $this->magicCommands;
    }

    protected function magicCommandCallAdd($name)
    {
        $this->magicCommands[] = $name;
    }

    /**
     * Magic method that implements.
     *
     * @param string $method
     * @param array  $args
     *
     * @throws \BadMethodCallException
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        foreach ($this->magicCommandCallList() as $mode) {
            $call = $this->magicCommandCall($mode, $method, $args);
            if ($call) {
                return $call;
            }
        }

        throw new \BadMethodCallException('There is no method ['.$method.']');
    }

    protected function magicCommandCall($mode, $method, $args)
    {
        $len = strlen($mode);
        $command = substr($method, 0, $len);
        if ($command === $mode) {
            $finalMethod = 'magic'.ucfirst($mode);
            $suplement = substr($method, $len);

            return $this->$finalMethod($suplement, current($args));
        }

        return false;
    }
}
