<?php

namespace Gpupo\CommonSdk;

use Gpupo\CommonSdk\Entity\Collection;

class Response extends Collection
{
    public function getData()
    {
        return new Collection(json_decode($this->get('responseRaw'), true));
    }
}
