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
use Gpupo\Common\Entity\CollectionAbstract;
use Gpupo\CommonSdk\Traits\FactoryTrait;

abstract class EntityAbstract extends CollectionAbstract
{
    use FactoryTrait;

    protected $requiredSchema = [];
    protected $optionalSchema = [];
    protected $previous;

    abstract public function getSchema();
    
    protected function setRequiredSchema(array $array = [])
    {
        $this->requiredSchema = $array;

        return $this;
    }

    protected function isRequired($key)
    {
        return in_array($key, $this->requiredSchema, true);
    }

    protected function isOptional($key)
    {
        return in_array($key, $this->optionalSchema, true);
    }

    protected function setOptionalSchema(array $array = [])
    {
        $this->optionalSchema = $array;

        return $this;
    }

    protected function setUp()
    {
    }

    public function __construct(array $data = null)
    {
        if (!$this instanceof EntityInterface) {
            throw new \Exception('EntityInterface deve ser implementada');
        }

        $schema = $this->getSchema();

        if (!empty($schema)) {
            parent::__construct($this->initSchema($this->getSchema(), $data));
        }

        $this->setUp();
    }

    protected function initSchema(array $schema, $data)
    {
        foreach ($schema as $key => $value) {
            if ($value === 'collection') {
                $schema[$key] = $this->factoryCollection(
                    EntityTools::getInitValue($data, $key, []));
            } elseif ($value === 'object') {
                $schema[$key] = $this->factoryNeighborObject(ucfirst($key),
                    EntityTools::getInitValue($data, $key, []));
            } elseif ($value === 'array') {
                $schema[$key] = EntityTools::getInitValue($data, $key, []);
            } elseif (in_array($value, ['string', 'integer', 'number', 'boolean'], true)) {
                $schema[$key] = EntityTools::normalizeType(EntityTools::getInitValue($data, $key), $value);
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
            $array = parent::toArray();

            foreach ($array as $key => $value) {
                if (empty($value) && $this->isOptional($key)) {
                    unset($array[$key]);
                }
            }

            return $array;
        }
    }

    public function schemaKeys()
    {
        return array_keys($this->getSchema());
    }

    public function schemaHasKey($key)
    {
        return in_array($key, $this->schemaKeys(), true);
    }

    protected function validate()
    {
        foreach ($this->getSchema() as $key => $value) {
            $current = $this->get($key);
            if ($current instanceof EntityInterface) {
                $current->validate();
            } else {
                EntityTools::validate($key, $current, $value, $this->isRequired($key));
            }
        }

        return true;
    }

    public function isValid()
    {
        try {
            return $this->validate();
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function setPrevious(EntityInterface $previous)
    {
        $this->previous = $previous;

        return $this;
    }

    public function getPrevious()
    {
        return $this->previous;
    }
}
