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

use Gpupo\CommonSdk\Entity\CollectionContainerInterface;
use Gpupo\CommonSdk\Exception\SchemaException;

class Tools
{
    public static function getInitValue($data, $key, $default = '')
    {
        if (is_array($data) && array_key_exists($key, $data)) {
            $fill = $data[$key];
            if (is_array($default) && !is_array($fill)) {
                $fill = [$key => $fill];
            }
        }

        return (isset($fill)) ? $fill : $default;
    }

    public static function normalizeType($data, $type)
    {
        switch ($type) {
            case 'integer':
                return (int) $data;
            case 'boolean':
                return (bool) $data;
            case 'number':
            case 'float':
                return (float) $data;
            default:
                return $data;
        }
    }

    public static function validate($key, $current, $value, $required = false, $prefix = '')
    {
        if (self::isEmptyValue($current, $required)) {
            return true;
        }

        foreach (['Integer', 'Number', 'String', 'Datetime'] as $type) {
            $testMethod = 'test'.$type;

            try {
                self::$testMethod($key, $current, $value);
            } catch (SchemaException $exception) {
                $exception->addMessagePrefix($prefix);

                throw $exception;
            }
        }

        return true;
    }

    public static function getRecursiveSchema($object)
    {
        $schema = $object->getSchema();
        foreach ($schema as $key => $value) {
            $current = $object->get($key);
            if ($current instanceof CollectionContainerInterface) {
                if (0 < $current->count()) {
                    $subObject = $current->first();
                } else {
                    $subObject = $current->factoryElement([]);
                }

                $schema[$key] = [self::getRecursiveSchema($subObject)];
            } elseif (method_exists($current, 'getSchema')) {
                $schema[$key] = self::getRecursiveSchema($current);
            }
        }

        return $schema;
    }

    protected static function returnInvalid($key, $current, $value)
    {
        throw new SchemaException('Validation Fail:['.$key
            .'] should have value of type ['.$value
            .'].Received:['.$current.'].');
    }

    protected static function isEmptyValue($value, $required = false)
    {
        return ($required) ? false : empty($value);
    }

    protected static function testInteger($key, $current, $value)
    {
        if ('integer' === $value && (int) $current !== $current) {
            self::returnInvalid($key, $current, $value);
        }
    }

    protected static function testNumber($key, $current, $value)
    {
        if ('number' === $value && !is_numeric($current)) {
            self::returnInvalid($key, $current, $value);
        }
    }

    protected static function testString($key, $current, $value)
    {
        if ('string' === $value && strlen((string) $current) < 1) {
            self::returnInvalid($key, $current, $value);
        }
    }

    protected static function testDatetime($key, $current, $value)
    {
        if ('datetime' === $value && strlen($current) < 10) {
            self::returnInvalid($key, $current, $value);
        }
    }
}
