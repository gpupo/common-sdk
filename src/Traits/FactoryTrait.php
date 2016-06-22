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
namespace Gpupo\CommonSdk\Traits;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\Common\Entity\CollectionAbstract;

trait FactoryTrait
{
    protected function factoryNewElement($className, $data)
    {
        if (
            $data instanceof CollectionAbstract
            && !in_array(EntityAbstract::class, class_parents($className))
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
            throw new \BadMethodCallException('There is no method ' . $method);
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
            if ( ! empty($data['found'])) {
                return $data['found'];
            }

            $errors .= $data['error'];
        }

        throw new \Exception('Class ' . $errors . ' not found');
    }

    protected static function resolvNeighborObject($calledClass, $objectName)
    {
        $error = '';
        $found = false;
        $list = explode('\\', $calledClass);
        end($list);
        $list[key($list)] = $objectName;
        $fullyQualified = implode('\\', $list);

        if ( ! class_exists($fullyQualified)) {
            $error .= $fullyQualified;
            $fullyQualified .= '\\' . $objectName;
        }

        if ( ! class_exists($fullyQualified)) {
            $error .= ' or ' . $fullyQualified;
        } else {
            $found = $fullyQualified;
        }

        return ['error' => $error, 'found' => $found];
    }
}
