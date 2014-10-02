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

    protected function initSchema(array $schema, $data)
    {
        foreach ($schema as $key => $value) {
            if ($value == 'collection') {
                $schema[$key] = $this->factoryCollection(
                    EntityTools::getInitValue($data, $key, []));
            } elseif ($value == 'object') {
                $schema[$key] = $this->factoryNeighborObject(ucfirst($key),
                    EntityTools::getInitValue($data, $key, []));
            } elseif ($value == 'array') {
                $schema[$key] = EntityTools::getInitValue($data, $key, []);
            } elseif (in_array($value, ['string', 'integer', 'number', 'boolean'])) {
                $schema[$key] = EntityTools::normalize(EntityTools::getInitValue($data, $key), $value);
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
            EntityTools::validate($this->get($key), $value);
        }

        return true;
    }
}
