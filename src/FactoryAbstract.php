<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\CommonSdk;

use Gpupo\Common\Traits\SingletonTrait;
use Psr\Log\LoggerInterface;

abstract class FactoryAbstract
{
    use SingletonTrait;

    protected $config;
    protected $logger;
    protected $client;

    abstract public function getNamespace();

    abstract protected function getSchema($namespace = null);

    public function __construct(array $config = [], LoggerInterface $logger = null)
    {
        $this->setup($config, $logger);
    }

    public function setup(array $config = [], LoggerInterface $logger = null)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    abstract public function setClient(array $clientOptions = []);

    public function getDelegateSchema($key)
    {
        return $this->resolvSchema($this->getSchema($this->getNamespace()), $key);
    }

    protected function resolvSchema(array $list, $key)
    {
        if (!array_key_exists($key, $list)) {
            throw new \BadMethodCallException('Faltando Factory Schema');
        }

        return $list[$key];
    }

    public function getClient()
    {
        if (!$this->client) {
            $this->setClient($this->config);
        }

        return $this->client;
    }

    public function factoryManager($className)
    {
        if (!class_exists($className)) {
            $schema = $this->getDelegateSchema($className);

            $className = $schema['manager'];
        }

        return new $className($this->getClient());
    }

    protected function forwardCallForMethod($schema, $data)
    {
        if (!method_exists($schema['class'], $schema['method'])) {
            throw new Exception\InvalidArgumentException('Method ['
                .$schema['class'].'::'.$schema['method'].'()] not found!');
        }

        return forward_static_call([$schema['class'], $schema['method']], $data);
    }

    protected function delegate($name, $data)
    {
        $schema = $this->getDelegateSchema($name);

        $className = $schema['class'];

        if (!class_exists($className)) {
            throw new Exception\InvalidArgumentException('Class ['
                .$className.'] not found!');
        }

        if (array_key_exists('method', $schema)) {
            return $this->forwardCallForMethod($schema, $data);
        }

        return new $className($data);
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @throws \BadMethodCallException
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        $command = substr($method, 0, 6);
        $object = substr($method, 6);
        $object[0] = strtolower($object[0]);

        if ($command === 'create') {
            return $this->delegate($object, current($args));
        } else {
            throw new \BadMethodCallException('There is no method '.$method);
        }
    }
}
