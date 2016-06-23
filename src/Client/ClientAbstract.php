<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * For more information, see <http://www.g1mr.com/common-sdk/>.
 */
namespace Gpupo\CommonSdk\Client;

use Gpupo\CommonSdk\Exception\RequestException;
use Gpupo\CommonSdk\Request;
use Gpupo\CommonSdk\Response;
use Gpupo\CommonSdk\Transport;

abstract class ClientAbstract extends BoardAbstract
{
    protected $mode;

    abstract protected function renderAuthorization();

    protected function renderContentType()
    {
        if ($this->getMode() === 'form') {
            $this->setMode(false);

            return 'Content-Type: application/x-www-form-urlencoded';
        }

        return 'Content-Type: application/json;charset=UTF-8';
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return array
     */
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

    /**
     * Executa a chamada http.
     *
     * @param Request $request Objeto com a requisição
     *
     * @return Response Objeto com a resposta da requisição
     */
    protected function exec(Request $request)
    {
        try {
            $data = $request->exec();
            $response = new Response($data);
            $response->setLogger($this->getLogger());
            $response->validate();

            $this->debug('Client Execution',
                [
                    'request'  => $request->toLog(),
                    'response' => $response->toLog(),
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

    protected function factoryPostRequest($resource, $body, $name = 'POST')
    {
        if (is_array($body)) {
            $body = http_build_query($body);
        }

        return $this->factoryRequest($resource, $name, true)->setBody($body);
    }

    /**
     * Executa uma requisição POST ou PUT.
     *
     * @param string       $resource Url de Endpoint
     * @param string|array $body     Valores do Request
     * @param string       $name     POST por default mas também pode ser usado para PUT
     *
     * @return Response
     */
    public function post($resource, $body, $name = 'POST')
    {
        return $this->exec($this->factoryPostRequest($resource, $body, $name));
    }

    /**
     * Executa uma requisição PUT, Facade para post().
     *
     * @param string       $resource Url de Endpoint
     * @param string|array $body     Valores do Request
     *
     * @return Response
     */
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

        $endpoint = $this->fillPlaceholdersWithOptions($base, ['version', 'protocol']);

        if (substr($resource, 0, 4) === 'http') {
            return $resource;
        } elseif ($resource[0] !== '/') {
            $endpoint .= '/';
        }

        return $endpoint . $resource;
    }
}
