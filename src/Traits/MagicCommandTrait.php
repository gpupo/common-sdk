<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
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
 *    //...
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
    protected $magicCommands = [];

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

    public function getSchema()
    {
        return [];
    }

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

    protected function magicCommandCall($mode, $method, $args)
    {
        $len = strlen($mode);
        $command = substr($method, 0, $len);
        if ($command === $mode) {
            $finalMethod = 'magic'.ucfirst($mode);
            $suplement = substr($method, $len);

            return $this->{$finalMethod}($suplement, current($args));
        }

        return false;
    }
}
