<?php

namespace Gpupo\CommonSdk;

use Psr\Log\LoggerInterface;
use Psr\Cache\ItemPoolInterface;
use Gpupo\CommonSdk\Exception\RequestException;

abstract class ClientAbstract
{
    use Traits\LoggerTrait;
    use Traits\CacheTrait;
    use Traits\SingletonTrait;
    use Traits\OptionsTrait;

    protected function factoryTransport()
    {
        return new Transport($this->getOptions());
    }

    public function factoryRequest($resource, $post = false)
    {
        $request = new Request;

        if ($post) {
            $request->setMethod('POST');
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
            $cacheItem = $this->getCacheItemPool()->get($key);

            if ($cacheItem->exists()) {
                return $cacheItem->get();
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

    public function post($resource, $body)
    {
        $this->destroyCache($resource);

        $request = $this->factoryRequest($resource, true)
            ->setBody($body);

        return $this->exec($request);
    }

    public function put($resource, $body)
    {
        $this->destroyCache($resource);

        $request = $this->factoryRequest($resource)->setBody($body)
            ->setMethod('PUT');

        return $this->exec($request);
    }
}
