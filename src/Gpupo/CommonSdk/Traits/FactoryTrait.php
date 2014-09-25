<?php
namespace Gpupo\CommonSdk\Traits;

trait FactoryTrait
{
    protected function factoryNeighborObject($objectName, $data)
    {
        $list = explode('\\', get_called_class());
        end($list);
        $list[key($list)] = $objectName;
        $object = implode('\\', $list);
        
        return new $object($data);
    }
}
