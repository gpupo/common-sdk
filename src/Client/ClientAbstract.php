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

namespace Gpupo\CommonSdk\Client;

use Gpupo\CommonSdk\Exception\ClientException;
use Gpupo\CommonSdk\Exception\RequestException;
use Gpupo\CommonSdk\Request;
use Gpupo\CommonSdk\Response;
use Gpupo\CommonSdk\Transport;

abstract class ClientAbstract extends BoardAbstract
{
    protected $mode;

    protected $endpoint_domain = 'api.localhost';

    public function getDefaultOptions(): array
    {
        return [
            'client_id' => false,
            'client_secret' => false,
            'access_token' => false,
            'user_id' => false,
            'refresh_token' => false,
            'users_url' => sprintf('https://%s/users', $this->endpoint_domain),
            'base_url' => sprintf('https://%s', $this->endpoint_domain),
            'oauth_url' => sprintf('https://%s/oauth', $this->endpoint_domain),
            'common_schema_namespace' => '\\Gpupo\\CommonSchema\\ORM',
            'verbose' => true,
            'cacheTTL' => 3600,
            'offset' => 0,
            'limit' => 30,
        ];
    }

    public function setMode($mode): void
    {
        $this->mode = $mode;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function factoryRequest(string $resource, string $method = '', bool $destroyCache = false): Request
    {
        if (false !== $destroyCache) {
            $this->destroyCache($resource);
        }

        $request = new Request();

        if (!empty($method)) {
            $request->setMethod($method);
        }

        $request
            ->setTransport($this->factoryTransport())
            ->setHeader($this->renderHeader())
            ->setUrl($this->getResourceUri($resource));

        return $request;
    }

    public function downloadFile(string $resource, string $filename = null)
    {
        $request = $this->factoryRequest($resource);
        $data = $request->exec();

        return file_put_contents($filename, $data['responseRaw']);
    }

    public function get(string $resource, int $ttl = null): Response
    {
        $request = $this->factoryRequest($resource);

        //Cache
        if (true === $ttl && $this->hasSimpleCache()) {
            $cacheId = $this->simpleCacheGenerateId($resource);

            if ($this->getSimpleCache()->has($cacheId)) {
                $response = $this->getSimpleCache()->get($cacheId);

                return $response;
            }

            $response = $this->exec($request);
            if (true === $ttl) {
                $this->getSimpleCache()->set($cacheId, $response, $this->getOptions()->get('cacheTTL', 3600));
                $jsonFile = sprintf('var/cache/get-%s.json', $cacheId);
                $fp = fopen($jsonFile, 'w');
                fwrite($fp, $response->get('responseRaw'));
                fclose($fp);
            }

            return $response;
        }

        return $this->exec($request);
    }

    /**
     * Executa uma requisição POST ou PUT.
     *
     * @param string       $resource Url de Endpoint
     * @param array|string $body     Valores do Request
     * @param string       $name     POST por default mas também pode ser usado para PUT
     *
     */
    public function post(string $resource, $body, string $name = 'POST'): Response
    {
        return $this->exec($this->factoryPostRequest($resource, $body, $name));
    }

    /**
     * Executa uma requisição PUT, Facade para post().
     *
     * @param string       $resource Url de Endpoint
     * @param array|string $body     Valores do Request
     *
     */
    public function put(string $resource, $body): Response
    {
        return $this->post($resource, $body, 'PUT');
    }

    /**
     * Executa uma requisição PATCH, Facade para post().
     *
     * @param string       $resource Url de Endpoint
     * @param array|string $body     Valores do Request
     *
     */
    public function patch(string $resource, $body): Response
    {
        return $this->post($resource, $body, 'PATCH');
    }

    public function getResourceUri($resource): string
    {
        $base = $this->getOptions()->get('base_url');

        if (empty($base) || \is_array($resource)) {
            return $this->normalizeResourceUri($resource);
        }

        $endpoint = $this->fillPlaceholdersWithOptions($base, ['version', 'protocol']);

        if ('http' === substr($resource, 0, 4)) {
            return $resource;
        }
        if ('/' !== $resource[0]) {
            $endpoint .= '/';
        }

        return $endpoint.$resource;
    }

    abstract protected function renderAuthorization(): array;

    protected function renderContentType(): array
    {
        $list = [];

        if ('form' === $this->getMode()) {
            $this->setMode(false);
            $list['Content-Type'] = 'application/x-www-form-urlencoded';
        } else {
            $list['Accept'] = 'application/json;charset=UTF-8';
            $list['Content-Type'] = 'application/json;charset=UTF-8';
        }

        return $list;
    }

    protected function renderHeader(): array
    {
        $list = [];

        foreach ([
            $this->renderContentType(),
            $this->renderAuthorization(),
        ] as $item) {
            if (\is_array($item)) {
                $list = array_merge($list, $item);
            }
        }

        return $list;
    }

    protected function factoryTransport(): Transport
    {
        $transport = new Transport($this->getOptions());

        $path = $this->getOptions()->get('registerPath', false);
        if ($path) {
            $transport->setRegisterPath($path);
        }

        return $transport;
    }

    /**
     * Executa a chamada http.
     */
    protected function exec(Request $request): Response
    {
        $this->log('debug', 'Client->exec->Request', $request->toLog());
        try {
            $data = $request->exec();
            $response = new Response($data);
            $this->log('debug', 'Client->exec->Response', $response->toLog());
            $response->validate();

            return $response;
        } catch (ClientException $e) {
            $this->log('debug', 'Client->exec->Exception', [
                'exception' => $e->toLog(),
                'request' => $request->toLog(),
            ]);

            $data = $response->getData();

            throw new RequestException(sprintf(
                'Type: %s, Message: %s, Status: %d, Method:%s, URI: %s',
                $data->get('error'),
                $data->get('message'),
                $data->get('status'),
                $request->get('method'),
                $request->get('url')
            ), (int) $data->get('status'), $e);
        }
    }

    protected function factoryPostRequest($resource, $body, $name = 'POST'): Request
    {
        if (\is_array($body)) {
            $body = http_build_query($body);
        }

        return $this->factoryRequest($resource, $name, true)->setBody($body);
    }

    protected function normalizeResourceUri($resource)
    {
        if (!\is_array($resource)) {
            return $resource;
        }

        foreach (['endpoint', 'url'] as $key) {
            if (array_key_exists($key, $resource) && !empty($resource[$key])) {
                return $resource[$key];
            }
        }

        return false;
    }
}
