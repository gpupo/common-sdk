<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\CommonSdk\Client;

use Gpupo\CommonSdk\Exception\ManagerException;
use Gpupo\CommonSdk\Map;
use Gpupo\CommonSdk\Response;
use Gpupo\CommonSdk\Traits\LoggerTrait;

abstract class ClientManagerAbstract
{
    use LoggerTrait;

    protected $client;

    protected $maps;

    protected $dryRun;

    public function __construct(ClientInterface $client = null)
    {
        if ($client) {
            $this->client = $client;
        }
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getDryRun()
    {
        return $this->dryRun;
    }

    protected function isDryRun()
    {
        return !empty($this->dryRun);
    }

    /**
     * Possibilita o uso de operações de gravação remota sem que estas sejam de
     * fato executadas. Isto é útil para testes.
     *
     * @param $value Gpupo\CommonSdk\Response|bool
     *
     * <code>
     * //Exemplo de uso
     * $manager = new Manager();
     * $manager->setDryRun()->save($entity);
     *
     * </code>
     */
    public function setDryRun($value = true)
    {
        $this->dryRun = $value;

        return $this;
    }

    protected function execute(Map $map, $body = null)
    {
        return $this->perform($map, $body);
    }

    /**
     * Encontra a URL e método para uma execução de Request.
     *
     * @param string $operation  Operação de execução (save, fetch)
     * @param array  $parameters Parâmetros que serão alocados nos placeholders
     */
    public function factoryMap($operation, array $parameters = null)
    {
        if (!is_array($this->maps)) {
            throw new ManagerException('Maps missed!');
        }

        if (!array_key_exists($operation, $this->maps)) {
            throw new ManagerException('Map ['.$operation.'] not found');
        }

        $data = $this->maps[$operation];
        if (!is_array($data)) {
            throw new ManagerException('Map MUST be array');
        }

        return new Map($data, $parameters);
    }

    protected function exceptionHandler(\Exception $exception, $method, $resource)
    {
        $text = $method.' on '.$resource.' FAIL:' .$exception->getMessage();

        $this->log('critical', $text, ['code' => $exception->getCode()]);

        return new ManagerException($text, $exception->getCode(), $exception);
    }

    /**
     * Possibilita hook com sobrecarga na implementação, para lidar com erros
     * que necessitam nova tentativa de execução.
     *
     * @param Exception $exception Exceção recebida no processo de execução do Request
     * @param int       $i         Numero da iteração para a mesma execução
     */
    protected function retry(\Exception $exception, $i)
    {
        if ($i === 1 && $exception->getCode() >= 500) {
            return true;
        }

        return false;
    }

    protected function perform(Map $map, $body = null)
    {
        $dryRun = $this->getDryRun();

        if (empty($dryRun)) {
            $this->log('debug', 'Perform:Real');

            return $this->performReal($map, $body);
        } elseif ($dryRun instanceof Response) {
            $this->log('debug', 'Perform:Mockup');

            return $dryRun;
        }

        $this->log('debug', 'Perform:bypass');

        return true;
    }

    protected function performReal(Map $map, $body = null)
    {
        $methodName = strtolower($map->getMethod());

        $i = 0;
        while ($i <= 5) {
            $i++;
            try {
                return $this->getClient()->$methodName($map->getResource(), $body);
            } catch (\Exception $exception) {
                if (!$this->retry($exception, $i)) {
                    throw $this->exceptionHandler($exception, $map->getMethod(),
                        $map->getResource());
                }
            }
        }
    }
}
