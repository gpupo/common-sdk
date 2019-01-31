<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\CommonSdk;

use Gpupo\Common\Interfaces\OptionsInterface;
use Gpupo\Common\Tools\Cache\SimpleCacheAwareTrait;
use Gpupo\Common\Traits\OptionsTrait;
use Gpupo\Common\Traits\SingletonTrait;
use Gpupo\CommonSchema\ORM\Entity\EntityInterface;
use Gpupo\CommonSdk\Client\ClientInterface;
use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Traits\LoggerTrait;
use Gpupo\CommonSdk\Traits\MagicCommandTrait;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

abstract class FactoryAbstract implements FactoryInterface
{
    use SingletonTrait;
    use OptionsTrait;
    use LoggerTrait;
    use MagicCommandTrait;
    use SimpleCacheAwareTrait;

    protected $name = 'common-sdk';

    protected $client;

    public function __construct(array $options = [], LoggerInterface $logger = null, CacheInterface $cache = null)
    {
        $this->setup($options, $logger, $cache);
    }

    public function setup(array $options = [], LoggerInterface $logger = null, CacheInterface $cache = null)
    {
        $this->setOptions($options);
        $this->initLogger($logger, $this->name);
        $this->initSimpleCache($cache);
        $this->magicCommandCallAdd('create');

        return $this;
    }

    public function setApplicationAPIClient(EntityInterface $ormClient): void
    {
        $this->getOptions()->set('client_id', $ormClient->getClientId());
        $this->getOptions()->set('client_secret', $ormClient->getClientSecret());

        if ($ormClient->hasAccessToken()) {
            $this->getOptions()->set('access_token', $ormClient->getAccessToken()->getAccessToken());
            $this->getOptions()->set('refresh_token', $ormClient->getAccessToken()->getRefreshToken());
            $this->getOptions()->set('user_id', $ormClient->getAccessToken()->getUserId());
        }

        if ($this->hasClient()) {
            $this->rebuildClient();
        }
    }

    abstract public function getNamespace();

    abstract public function setClient(array $clientOptions = []);

    public function getDelegateSchema($key)
    {
        return $this->resolvSchema($this->getSchema($this->getNamespace()), $key);
    }

    public function getClient()
    {
        if (!$this->hasClient()) {
            $this->buildClient();
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

        if ($manager instanceof OptionsInterface) {
            $manager->receiveOptions($this->getOptions());
        }

        if ($this->getLogger()) {
            $manager->initLogger($this->getLogger());
        }

        return $manager;
    }

    protected function hasClient(): bool
    {
        return $this->client instanceof ClientInterface;
    }

    protected function buildClient(): void
    {
        $array = $this->getOptions()->toArray();
        $array['tag'] = 'build';
        $this->setClient($array);
    }

    protected function rebuildClient(): void
    {
        $this->client->receiveOptions($this->getOptions());
    }

    abstract protected function getSchema(): array;

    protected function magicCreate($suplement, $input)
    {
        return $this->delegate($suplement, $input);
    }

    /**
     * Encontra as configurações para criação de objeto, implementadas (array) em getSchema();.
     *
     * @param mixed $key
     */
    protected function resolvSchema(array $list, $key)
    {
        $key[0] = strtolower($key[0]);

        if (!array_key_exists($key, $list)) {
            throw new \BadMethodCallException('Faltando Factory ['.$key.'] no Schema ['.implode(' ', array_keys($list)).']');
        }

        return $list[$key];
    }

    protected function forwardCallForMethod($schema, $data)
    {
        if (!method_exists($schema['class'], $schema['method'])) {
            throw new Exception\InvalidArgumentException('Method ['.$schema['class'].'::'.$schema['method'].'()] not found!');
        }

        return forward_static_call([$schema['class'], $schema['method']], $data);
    }

    protected function delegate($name, $data)
    {
        $schema = $this->getDelegateSchema($name);

        $className = $schema['class'];

        if (!class_exists($className)) {
            throw new Exception\InvalidArgumentException('Class ['.$className.'] not found!');
        }

        if (array_key_exists('method', $schema)) {
            return $this->forwardCallForMethod($schema, $data);
        }

        if (empty($data)) {
            $entity = new $className();
        } else {
            $entity = new $className($data);
        }

        return $this->decoratorEntity($entity);
    }

    protected function decoratorEntity($entity)
    {
        if ($entity instanceof EntityAbstract) {
            $entity->setLogger($this->getLogger());
        }

        return $entity;
    }
}
