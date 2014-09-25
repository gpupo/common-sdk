<?php
namespace Gpupo\CommonSdk\Traits;

trait FactoryTrait
{
    protected function factoryNeighborObject($objectName, $data)
    {
        $object = static::getFullyQualifiedNeighborObject(get_called_class(),
            $objectName);
        
        return new $object($data);
    }
    
    public static function __callStatic($method, $args)
    {
        $command = substr($method, 0, 7);
        $objectName = substr($method, 7);

        if ($command == "factory") {
            return self::factory($objectName, current($args), next($args));
        } else {
            throw new \BadMethodCallException("There is no method ".$method);
        }
    }
    
    public static function factory($objectName, $data = null)
    {
        $object = self::getFullyQualifiedNeighborObject(get_called_class(),
            $objectName);
        
        return new $object($data);
    }
    
    protected static function getFullyQualifiedNeighborObject($calledClass, $objectName)
    {
        $list = explode('\\', $calledClass);
        end($list);
        $list[key($list)] = $objectName;
        $fullyQualified = implode('\\', $list);
        
        if (!class_exists($fullyQualified)) {
            $error = $fullyQualified;
            $fullyQualified .= '\\' . $objectName;
        }
        
        if (!class_exists($fullyQualified)) {
            $error .= ' or ' . $fullyQualified;
            
            throw new \Exception('Class ' . $error . ' not found');
        }
        
        return $fullyQualified;
    }

}
