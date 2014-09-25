<?php

namespace Gpupo\CommonSdk\Entity;

use Doctrine\Common\Collections\ArrayCollection;

abstract class CollectionAbstract extends ArrayCollection
{
    use \Gpupo\CommonSdk\Traits\MagicCallTrait;

    public function toArray()
    {
        $list = parent::toArray();

        foreach ($list as $key => $value) {
            if ($value instanceof CollectionAbstract) {
                $list[$key] = $value->toArray();
            }
        }

        return $list;
    }

    public function toJson($route = null)
    {
        if (empty($route) || $route == 'save') {
            $data = $this->toArray();
        } else {
            $method = 'to' . ucfirst($route);
            $data = $this->$method();
        }
        
        return json_encode($data);
    }

    public function toLog()
    {
        return $this->toArray();
    }
}
