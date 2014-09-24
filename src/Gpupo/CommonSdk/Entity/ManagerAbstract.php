<?php

namespace Gpupo\CommonSdk\Entity;

use Gpupo\CommonSdk\ClientInterface;
use Gpupo\CommonSdk\Exception\ManagerException;

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

    protected function exceptionHandler(\Exception $exception, $method, $resource)
    {
        return new ManagerException($method . ' on ' . $resource . ' FAIL:'
            . $exception->getMessage(), $exception->getCode(), $exception);
    }

    protected function perform($method, $resource, $body = null)
    {
        $methodName = strtolower($method);

        try {
            return $this->getClient()->$methodName($resource, $body);
        } catch (\Exception $exception) {
            throw $this->exceptionHandler($exception, $method, $resource);
        }
    }

    protected function execute($method, $resource, $body = null)
    {
        $this->perform($method, $resource, $body);

        return true;
    }
}
