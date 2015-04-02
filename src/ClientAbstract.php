<?php

/*
 * This file is part of common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\CommonSdk;

use Gpupo\Common\Traits\OptionsTrait;
use Gpupo\Common\Traits\SingletonTrait;
use Gpupo\CommonSdk\Exception\RequestException;
use Psr\Log\LoggerInterface;

/**
 * @method string getResourceUri(string $resource)
 */
abstract class ClientAbstract
{
    use Traits\LoggerTrait;
    use Traits\CacheTrait;
    use SingletonTrait;
    use OptionsTrait;

    protected function factoryTransport()
    {
        return new Transport($this->getOptions());
    }

    public function factoryRequest($resource, $method = '', $destroyCache = false)
    {
        if ($destroyCache) {
            $this->destroyCache($resource);
        }

        $request = new Request();

        if (!empty($method)) {
            $request->setMethod($method);
        }

        $request->setTransport($this->factoryTransport())
            ->setUrl($this->getResourceUri($resource));

        return $request;
    }

    public function __construct($options = [], LoggerInterface $logger = null, CacheItemPoolInterface $cacheItemPool = null)
    {
        $this->setOptions($options);
        $this->initLogger($logger);
        $this->initCache($cacheItemPool);
    }

    protected function exec(Request $request)
    {
        try {
            $data = $request->exec();
            $response = new Response($data);
            $response->setLogger($this->getLogger());
            $response->validate();

            $this->debug('Client Execution',
                [
                    'request'   => $request->toLog(),
                    'response'  => $response->toLog(),
                ]
            );

            return $response;
        } catch (RequestException $e) {
            $this->error('Execucao fracassada', [
                'exception' => $e->toLog(),
                'request'   => $request->toLog(),
            ]);

            throw $e;
        }
    }

    public function get($resource, $ttl = null)
    {
        $request = $this->factoryRequest($resource);

        if ($ttl && $this->hasCacheItemPool()) {
            $key = $this->factoryCacheKey($resource);
            $cacheItem = $this->getCacheItemPool()->getItem($key);

            if ($cacheItem->exists()) {
                $response = $cacheItem->get();

                return $response;
            }

            $response = $this->exec($request);
            if ($ttl === true) {
                $ttl = $this->getOptions()->get('cacheTTL', 3600);
                $cacheItem->set($response, $ttl);
                $this->getCacheItemPool()->save($cacheItem);
            }

            return $response;
        } else {
            return $this->exec($request);
        }
    }

    protected function destroyCache($resource)
    {
        if ($this->hasCacheItemPool()) {
            $key = $this->factoryCacheKey($resource);
            $this->getCacheItemPool()->deleteItens([$key]);
        }

        return $this;
    }

    public function post($resource, $body, $name = 'POST')
    {
        $request = $this->factoryRequest($resource, $name, true)->setBody($body);

        return $this->exec($request);
    }

    public function put($resource, $body)
    {
        return $this->post($resource, $body, 'PUT');
    }
    
    public function getResourceUri($resource)
    {
        $url = $this->getOptions()->get('base_url');
        $version = $this->getOptions()->get('version');
        $endpoint = str_replace('{VERSION}', $version, $url);

        if ($resource[0] !== '/') {
            $endpoint .= '/';
        }

        return $endpoint.$resource;
    }
}
