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

    public function __construct(array $config = [], LoggerInterface $logger = null)
    {
        $this->setup($config, $logger);
    }

    public function setup(array $config = [], LoggerInterface $logger = null)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    abstract public function setClient();

    abstract public function getNamespace();

    public function getDelegateSchema($key)
    {
        $list = $this->getGenericSchemaByNamespace($this->getNamespace());

        return $this->resolvSchema($list, $key);
    }

    protected function resolvSchema(array $list, $key)
    {
        if (!array_key_exists($key, $list)) {
            throw new \BadMethodCallException('Faltando Factory Schema');
        }

        return $list[$key];
    }

    protected function getGenericSchemaByNamespace($namespace)
    {
        return [
            'product' => [
                'class'     => $namespace.'Product\Factory',
                'method'    => 'factoryProduct',
                'manager'   => $namespace.'Product\Manager',
            ],
            'sku' => [
                'class'     => $namespace.'Product\Factory',
                'method'    => 'factorySku',
                'manager'   => $namespace.'Product\Sku\Manager',
            ],
            'order' => [
                'class'     => $namespace.'Order\Factory',
                'method'    => 'factoryOrder',
                'manager'   => $namespace.'Order\Manager',
            ],
        ];
    }

    public function getClient()
    {
        if (!$this->client) {
            $this->setClient();
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

    protected function delegate($name, $data)
    {
        $schema = $this->getDelegateSchema($name);

        return forward_static_call([$schema['class'], $schema['method']], $data);
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
