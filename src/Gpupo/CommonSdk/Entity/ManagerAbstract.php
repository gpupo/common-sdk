<?php

namespace Gpupo\CommonSdk\Entity;

use Gpupo\CommonSdk\ClientInterface;
use Gpupo\CommonSdk\Exception\ManagerException;
use Gpupo\CommonSdk\Map;

abstract class ManagerAbstract
{
    protected $client;
    
    protected $maps;
    
    public function save(EntityInterface $entity)
    {
        return $this->execute($this->factoryMap('save'), $entity->toJson());
    }

    public function findById($itemId)
    {       
        $response =  $this->perform($this->factoryMap('findById',
            ['itemId' => $itemId]));
        $product = new Product($response->getData()->toArray());

        return $product;
    }

    public function fetch($offset = 1, $limit = 50)
    {
        $response =  $this->perform($this->factoryMap('fetch',
            ['offset' => $offset, 'limit' => $limit]));

        $product = new Product($response->getData()->toArray());

        return $product;
    }

    public function factoryMap($operation, array $parameters = null)
    {
        if (!is_array($this->maps)) {
            throw new ManagerException('Maps missed!');
        }
        
        if (!array_key_exists($operation, $this->maps)) {
            throw new ManagerException('Map not found');
        }
        
        $data = $this->maps[$operation];
        if (!is_array($data)) {
            throw new ManagerException('Map MUST be array');
        }
        
        return new Map($data, $parameters);        
    }

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    protected function exceptionHandler(\Exception $exception, $method, $resource)
    {
        return new ManagerException($method . ' on ' . $resource . ' FAIL:'
            . $exception->getMessage(), $exception->getCode(), $exception);
    }

    protected function perform(Map $map, $body = null)
    {
        $methodName = strtolower($map->getMethod());

        try {
            return $this->getClient()->$methodName($map->getResource(), $body);
        } catch (\Exception $exception) {
            throw $this->exceptionHandler($exception, $map->getMethod(),
                $map->getResource());
        }
    }

    protected function execute(Map $map, $body = null)
    {
        $this->perform($map, $body);

        return true;
    }
}
