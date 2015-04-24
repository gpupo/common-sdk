<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\CommonSdk\Client;

use Gpupo\CommonSdk\Exception\RequestException;
use Gpupo\CommonSdk\Request;
use Gpupo\CommonSdk\Response;
use Gpupo\CommonSdk\Transport;

abstract class ClientAbstract extends BoardAbstract
{
    abstract protected function renderAuthorization();

    protected function renderContentType()
    {
        return 'Content-Type: application/json;charset=UTF-8';
    }

    protected function renderHeader()
    {
        $list = [];

        foreach ([
            $this->renderAuthorization(),
            $this->renderContentType(),
        ] as $item) {
            if (is_array($item)) {
                $list = array_merge($list, $item);
            } elseif (!empty($item)) {
                $list[] = $item;
            }
        }

        return $list;
    }

    protected function factoryTransport()
    {
        $transport = new Transport($this->getOptions());

        $path = $this->getOptions()->get('registerPath', false);
        if ($path) {
            $transport->setRegisterPath($path);
        }

        return $transport;
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
            ->setHeader($this->renderHeader())
            ->setUrl($this->getResourceUri($resource));

        return $request;
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

    public function post($resource, $body, $name = 'POST')
    {
        $request = $this->factoryRequest($resource, $name, true)->setBody($body);

        return $this->exec($request);
    }

    public function put($resource, $body)
    {
        return $this->post($resource, $body, 'PUT');
    }

    protected function normalizeResourceUri($resource)
    {
        if (!is_array($resource)) {
            return $resource;
        }

        foreach (['endpoint', 'url'] as $key) {
            if (array_key_exists($key, $resource) && !empty($resource[$key])) {
                return $resource[$key];
            }
        }

        return false;
    }

    public function getResourceUri($resource)
    {
        $base = $this->getOptions()->get('base_url');

        if (empty($base) || is_array($resource)) {
            return $this->normalizeResourceUri($resource);
        }

        $endpoint = $this->fillPlaceholdersWithOptions($base, ['version']);

        if ($resource[0] !== '/') {
            $endpoint .= '/';
        }

        return $endpoint.$resource;
    }
}
