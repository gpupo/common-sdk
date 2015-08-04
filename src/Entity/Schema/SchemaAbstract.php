<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For more information, see
 * <http://www.g1mr.com/common-sdk/>.
 */

namespace Gpupo\CommonSdk\Entity\Schema;

use Gpupo\Common\Entity\Collection;
use Gpupo\Common\Entity\CollectionAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\CommonSdk\Exception\SchemaException;
use Gpupo\CommonSdk\Traits\DocumentationTrait;
use Gpupo\CommonSdk\Traits\FactoryTrait;
use Gpupo\CommonSdk\Traits\LoggerTrait;

/**
 * @method log(string $level, string $string, array $context)
 */
abstract class SchemaAbstract extends CollectionAbstract
{
    use FactoryTrait;
    use DocumentationTrait;
    use LoggerTrait;

    protected $requiredSchema = [];
    protected $optionalSchema = [];

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

    protected function factoryCollection($data = [])
    {
        return new Collection($data);
    }

    protected function initSchema(array $schema, $data)
    {
        foreach ($schema as $key => $value) {
            if ($value === 'collection') {
                $schema[$key] = $this->factoryCollection(
                    Tools::getInitValue($data, $key, []));
            } elseif ($value === 'object') {
                $schema[$key] = $this->factoryNeighborObject(ucfirst($key),
                Tools::getInitValue($data, $key, []));
            } elseif ($value === 'array') {
                $schema[$key] = Tools::getInitValue($data, $key, []);
            } elseif (in_array($value, ['string', 'integer', 'number', 'boolean'], true)) {
                $schema[$key] = Tools::normalizeType(Tools::getInitValue($data, $key), $value);
            }
        }

        return $schema;
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
                Tools::validate($key, $current, $value, $this->isRequired($key), $this->getCalledEntityName());
            }
        }

        return true;
    }

    public function getCalledEntityName($fullyQualified = null)
    {
        $calledClass =  get_called_class();

        if ($fullyQualified) {
            return $calledClass;
        }

        $list = explode('\\', $calledClass);

        return end($list);
    }

    public function isValid()
    {
        try {
            return $this->validate();
        } catch (SchemaException $exception) {
            $this->log('WARNING', 'Validation Fail',  $exception->toLog());

            return false;
        }
    }
}
