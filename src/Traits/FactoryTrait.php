<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\CommonSdk\Traits;

use Gpupo\Common\Entity\CollectionAbstract;
use Gpupo\CommonSdk\Entity\EntityAbstract;

trait FactoryTrait
{
    protected function factoryNewElement($className, $data)
    {
        if (
            $data instanceof CollectionAbstract
            && !in_array(EntityAbstract::class, class_parents($className), true)
        ) {
            $data = $data->toArray();
        }

        return new $className($data);
    }

    protected function factoryNeighborObject($objectName, $data)
    {
        $className = static::getFullyQualifiedNeighborObject(get_called_class(),
            $objectName);

        return $this->factoryNewElement($className, $data);
    }

    public static function __callStatic($method, $args)
    {
        $command = substr($method, 0, 7);
        $objectName = substr($method, 7);

        if ($command === 'factory') {
            return self::factory($objectName, current($args), next($args));
        } else {
            throw new \BadMethodCallException('There is no method '.$method);
        }
    }

    /**
     * @param string $objectName
     */
    public static function factory($objectName, $data = null)
    {
        $object = self::getFullyQualifiedNeighborObject(get_called_class(),
            $objectName);

        return new $object($data);
    }

    /**
     * @param string $calledClass
     */
    protected static function getFullyQualifiedNeighborObject($calledClass, $objectName)
    {
        $errors = '';
        $entityRepository = [$calledClass];
        $entityRepository[] = get_parent_class($calledClass);

        foreach ($entityRepository as $class) {
            $data = self::resolvNeighborObject($class, $objectName);
            if (!empty($data['found'])) {
                return $data['found'];
            }

            $errors .= $data['error'];
        }

        throw new \Exception('Class '.$errors.' not found');
    }

    protected static function resolvNeighborObject($calledClass, $objectName)
    {
        $error = '';
        $found = false;
        $list = explode('\\', $calledClass);
        end($list);
        $list[key($list)] = $objectName;
        $fullyQualified = implode('\\', $list);

        if (!class_exists($fullyQualified)) {
            $error .= $fullyQualified;
            $fullyQualified .= '\\'.$objectName;
        }

        if (!class_exists($fullyQualified)) {
            $error .= ' or '.$fullyQualified;
        } else {
            $found = $fullyQualified;
        }

        return ['error' => $error, 'found' => $found];
    }
}
