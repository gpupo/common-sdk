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

namespace Gpupo\CommonSdk\Traits;

use Gpupo\Common\Entity\CollectionAbstract;
use Gpupo\CommonSdk\Entity\EntityAbstract;

trait FactoryTrait
{
    public static function __callStatic($method, $args)
    {
        $command = substr($method, 0, 7);
        $objectName = substr($method, 7);

        if ('factory' === $command) {
            return self::factory($objectName, current($args), next($args));
        }

        throw new \BadMethodCallException('There is no method '.$method);
    }

    /**
     * @param string     $objectName
     * @param null|mixed $data
     */
    public static function factory($objectName, $data = null)
    {
        $object = self::getFullyQualifiedNeighborObject(
            \get_called_class(),
            $objectName
        );

        return new $object($data);
    }

    protected function factoryNewElement($className, $data)
    {
        if ($data instanceof CollectionAbstract
            && !\in_array(EntityAbstract::class, class_parents($className), true)
        ) {
            $data = $data->toArray();
        }

        return new $className($data);
    }

    protected function factoryNeighborObject($objectName, $data)
    {
        $className = static::getFullyQualifiedNeighborObject(
            \get_called_class(),
            $objectName
        );

        return $this->factoryNewElement($className, $data);
    }

    /**
     * @param string $calledClass
     * @param mixed  $objectName
     */
    protected static function getFullyQualifiedNeighborObject($calledClass, $objectName)
    {
        $errors = '';
        $entityRepository = ['main' => $calledClass];
        $entityRepository['lv1'] = get_parent_class($calledClass);
        $entityRepository['lv2'] = get_parent_class($entityRepository['lv1']);

        foreach ($entityRepository as $class) {
            $data = self::resolvNeighborObject($class, $objectName);
            if (!empty($data['found'])) {
                return $data['found'];
            }

            $errors .= $data['error'];
        }

        throw new \Exception(sprintf('Class %s not found. Searched on %s', $errors, implode("\n", $entityRepository)));
    }

    protected static function resolvNeighborObject($calledClass, $objectName)
    {
        if (false !== strpos($objectName, '_')) {
            $explode = explode('_', $objectName);
            $normalized = array_map('ucfirst', $explode);
            $objectName = implode('', $normalized);
        }

        $error = '';
        $found = false;
        $list = explode('\\', $calledClass);
        end($list);
        $list[key($list)] = $objectName;
        $fullyQualified = implode('\\', $list);

        $acceptedClasses = [
            $fullyQualified,
            sprintf('%sCollection', $fullyQualified),
            sprintf('%s\%sCollection', $fullyQualified, $objectName),
            sprintf('%s\Collection', $fullyQualified, $objectName),
            sprintf('%s\%s', $fullyQualified, $objectName),
        ];

        foreach ($acceptedClasses as $try) {
            if (class_exists($try)) {
                $found = $try;

                break;
            }
        }

        if (false === $found) {
            $error = "Class not found. \n\t Searched: \n\t- ".implode("\n\t- ", $acceptedClasses)."\n";
        }

        return ['error' => $error, 'found' => $found];
    }
}
