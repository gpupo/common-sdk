<?php

namespace Gpupo\CommonSdk\Entity;

use Gpupo\CommonSdk\Traits\FactoryTrait;

abstract class EntityAbstract extends CollectionAbstract
{
    use FactoryTrait;

    public function __construct(array $data = null)
    {
        if (!$this instanceof EntityInterface) {
            throw new \Exception('EntityInterface deve ser implementada');
        }

        $schema = $this->getSchema();

        if (!empty($schema)) {
            parent::__construct($this->initSchema($this->getSchema(), $data));
        }
    }

    protected function getInitValue($data, $key, $default = '')
    {
        if (array_key_exists($key, $data)) {
            $fill = $data[$key];
            if (is_array($default) && !is_array($fill)) {
                $fill = [$key => $fill];
            }
        }

        return (isset($fill)) ? $fill : $default;
    }
    
    protected function initSchema(array $schema, $data)
    {        
        foreach ($schema as $key => $value) {
            if ($value == 'collection') {
                $schema[$key] = $this->factoryCollection(
                    $this->getInitValue($data, $key, []));
            } elseif ($value == 'object') {
                $schema[$key] = $this->factoryNeighborObject(ucfirst($key),
                    $this->getInitValue($data, $key, []));
            } elseif ($value == 'array') {
                $schema[$key] = $this->getInitValue($data, $key, []);
            } elseif (in_array($value, ['string', 'integer', 'number'])) {
                $schema[$key] = $this->getInitValue($data, $key);
            }
        }

        return $schema;
    }

    protected function factoryCollection($data = [])
    {
        return new Collection($data);
    }

    public function toArray()
    {
        if ($this->validate()) {
            return parent::toArray();
        }
    }

    protected function validate()
    {
        foreach ($this->getSchema() as $key => $value) {
            $current = $this->get($key);
            if ($value == 'integer') {
                if (!empty($current) && intval($current) !== $current) {
                    throw new \InvalidArgumentException($key 
                        . ' should have value of type Integer valid (received ' 
                        . $current . ')');
                }
            } elseif (!empty($current) && $value == 'number') {
                if (!is_numeric($current)) {
                    throw new \InvalidArgumentException($key 
                        . ' should have value of type Number valid');
                }
            }
        }

        return true;
    }
}
