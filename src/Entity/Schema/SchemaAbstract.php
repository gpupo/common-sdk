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

    abstract public function getSchema(): array;

    public function schemaKeys()
    {
        return array_keys($this->getSchema());
    }

    public function schemaHasKey($key)
    {
        return \in_array($key, $this->schemaKeys(), true);
    }

    public function getCalledEntityName($fullyQualified = null)
    {
        $calledClass = \get_called_class();

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
            $this->log('WARNING', 'Validation Fail', $exception->toLog());

            return false;
        }
    }

    protected function setRequiredSchema(array $array = [])
    {
        $this->requiredSchema = $array;

        return $this;
    }

    protected function isRequired($key)
    {
        return \in_array($key, $this->requiredSchema, true);
    }

    protected function isOptional($key)
    {
        return \in_array($key, $this->optionalSchema, true);
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
            if ('collection' === $value) {
                $iv = $ov = Tools::getInitValue($data, $key, []);
                if ('s' === mb_substr($key, -1)) {
                    try {
                        $iv = [];
                        foreach ($ov as $y) {
                            $iv[] = $this->factoryNeighborObject(ucfirst(rtrim($key, 's')), $y);
                        }
                    } catch (\Exception $e) {
                        $iv = $ov;
                    }
                }

                $schema[$key] = $this->factoryCollection($iv);
            } elseif (Tools::isObjectType($value)) {
                $schema[$key] = $this->factoryNeighborObject(
                    ucfirst($key),
                    Tools::getInitValue($data, $key, [])
                );
            } elseif ('array' === $value) {
                $schema[$key] = Tools::getInitValue($data, $key, []);
            } elseif (\in_array($value, ['string', 'integer', 'number', 'boolean', 'datetime'], true)) {
                $schema[$key] = Tools::normalizeType(Tools::getInitValue($data, $key), $value);
            }
        }

        return $schema;
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
}
