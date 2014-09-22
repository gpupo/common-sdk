<?php

namespace Gpupo\CommonSdk\Entity;

use Gpupo\CommonSdk\ClientInterface;

abstract class ManagerAbstract
{
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }
}
