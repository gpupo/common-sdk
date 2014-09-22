<?php

namespace Gpupo\CommonSdk\Entity;

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