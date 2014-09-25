<?php

namespace Gpupo\CommonSdk\Entity;

use Gpupo\CommonSdk\ClientInterface;
use Gpupo\CommonSdk\Exception\ManagerException;
use Gpupo\CommonSdk\Map;
use Gpupo\CommonSdk\Traits\FactoryTrait;

abstract class ManagerAbstract
{
    use FactoryTrait;
    
    protected $client;
    
    protected $maps;
    
    public function save(EntityInterface $entity, $route = 'save')
    {
        return $this->execute($this->factoryMap($route), $entity->toJson());
    }

    public function findById($itemId)
    {       
        $response =  $this->perform($this->factoryMap('findById',
            ['itemId' => $itemId]));
        
        return $response->getData();
    }

    public function fetch($offset = 1, $limit = 50)
    {
        $response =  $this->perform($this->factoryMap('fetch',
            ['offset' => $offset, 'limit' => $limit]));

        return $response->getData();
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
        $command = substr($method, 0, 4);
        $field = substr($method, 4);
        $field[0] = strtolower($field[0]);

        if ($command == "save") {
            return $this->save(current($args), $field);
        } else {
            throw new \BadMethodCallException("There is no method ".$method);
        }
    }
}
