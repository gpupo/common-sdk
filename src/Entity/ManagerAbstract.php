<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\CommonSdk\Entity;

use Gpupo\Common\Entity\Collection;
use Gpupo\CommonSdk\Client\ClientInterface;
use Gpupo\CommonSdk\Exception\ManagerException;
use Gpupo\CommonSdk\Map;
use Gpupo\CommonSdk\Traits\EntityDiffTrait;
use Gpupo\CommonSdk\Traits\FactoryTrait;

abstract class ManagerAbstract
{
    use FactoryTrait;
    use EntityDiffTrait;

    protected $client;

    protected $maps;

    public function save(EntityInterface $entity, $route = 'save')
    {
        $existent = $entity->getPrevious() ?: $this->findById($entity->getId());

        if ($existent) {
            return $this->update($entity, $existent);
        }

        return $this->execute($this->factoryMap($route), $entity->toJson($route));
    }

    public function update(EntityInterface $entity, EntityInterface $existent)
    {
        throw new ManagerException('Update must be implemented!');
    }

    public function findById($itemId)
    {
        try {
            $response =  $this->perform($this->factoryMap('findById',
            ['itemId' => $itemId]));

            return $response->getData();
        } catch (ManagerException $exception) {
            return false;
        }
    }

    public function fetch($offset = 0, $limit = 50, array $parameters = [])
    {
        $response =  $this->perform($this->factoryMap('fetch',
            array_merge($parameters, ['offset' => $offset, 'limit' => $limit])));

        return $response->getData();
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

    protected function exceptionHandler(\Exception $exception, $method, $resource)
    {
        return new ManagerException($method.' on '.$resource.' FAIL:'
            .$exception->getMessage(), $exception->getCode(), $exception);
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
            sleep(5);

            return true;
        }

        return false;
    }

    protected function perform(Map $map, $body = null)
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

    protected function factoryCollection(array $list)
    {
        return new Collection($list);
    }

    protected function execute(Map $map, $body = null)
    {
        $this->perform($map, $body);

        return true;
    }

    protected function getEntityName()
    {
        if (empty($this->entity)) {
            throw new ManagerException('Entity missed!');
        }

        return $this->entity;
    }

    protected function factoryEntityCollection(array $data)
    {
        $list = [];
        foreach ($data as $item) {
            if (is_array($item)) {
                $list[] = $this->factoryEntity($item);
            }
        }

        return $this->factoryCollection($list);
    }

    protected function factoryEntity(array $data = null)
    {
        return $this->factoryNeighborObject($this->getEntityName(), $data);
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
        $command = substr($method, 0, 4);
        $field = substr($method, 4);
        $field[0] = strtolower($field[0]);

        if ($command === 'save') {
            return $this->save(current($args), $method);
        } else {
            throw new \BadMethodCallException('There is no method '.$method);
        }
    }
}
