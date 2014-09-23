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

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toLog()
    {
        return $this->toArray();
    }    
}
