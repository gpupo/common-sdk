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
use Gpupo\CommonSdk\Traits\LoggerTrait;
use Gpupo\CommonSdk\Traits\MagicCommandTrait;
use Psr\Log\LoggerInterface;
use Gpupo\CommonSdk\Entity\EntityAbstract;

abstract class FactoryAbstract
{
    use SingletonTrait;
    use LoggerTrait;
    use MagicCommandTrait;

    protected $config;

    protected $client;

    abstract public function getNamespace();

    /**
     * @return array
     */
    abstract protected function getSchema($namespace = null);

    public function __construct(array $config = [], LoggerInterface $logger = null)
    {
        $this->setup($config, $logger);
    }

    protected function magicCreate($suplement, $input)
    {
        return $this->delegate($suplement, $input);
    }

    public function setup(array $config = [], LoggerInterface $logger = null)
    {
        $this->config = $config;
        $this->initLogger($logger);
        $this->magicCommandCallAdd('create');
    }

    abstract public function setClient(array $clientOptions = []);

    public function getDelegateSchema($key)
    {
        return $this->resolvSchema($this->getSchema($this->getNamespace()), $key);
    }

    /**
     * Encontra as configurações para criação de objeto, implementadas (array) em getSchema();.
     */
    protected function resolvSchema(array $list, $key)
    {
        $key[0] = strtolower($key[0]);

        if (!array_key_exists($key, $list)) {
            throw new \BadMethodCallException('Faltando Factory ['.$key
                .'] no Schema ['.implode(' ', array_keys($list)).']');
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

        $manager = new $className($this->getClient());

        if ($this->getLogger()) {
            $manager->initLogger($this->getLogger());
        }

        return $manager;
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

        $entity = new $className($data);

        return $this->decoratorEntity($entity);
    }

    protected function decoratorEntity(EntityAbstract $entity)
    {
        $entity->setLogger($this->getLogger());

        return $entity;
    }
}
