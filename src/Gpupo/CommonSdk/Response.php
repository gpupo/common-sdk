<?php

namespace Gpupo\CommonSdk;

use Gpupo\CommonSdk\Entity\Collection;

class Response extends Collection
{
    public function getData()
    {
        return new Collection(json_decode($this->get('responseRaw'), true));
    }

    public function toLog()
    {
        return [
            'raw' => str_replace('"', '', $this->getResponseRaw()),
            'statusCode'    => $this->getHttpStatusCode(),
            'requestInfo'   => $this->getRequestInfo(),
        ];
    }
}
