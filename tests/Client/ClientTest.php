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

namespace Gpupo\CommonSdk\Tests\Client;

use Gpupo\CommonSdk\Client\Client;
use Gpupo\CommonSdk\Request;
use Gpupo\CommonSdk\Tests\TestCaseAbstract;
use Monolog\Logger;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Gpupo\Common\Tools\Reflected;
/**
 * @coversNothing
 */
class ClientTest extends TestCaseAbstract
{
    public function testUrlIndependenteDeConfiguracao()
    {
        $client = new Client();
        $this->assertSame(
            'https://api.localhost/sku',
            $client->getResourceUri('/sku')
        );
    }

    public function testUrlBaseadoEmConfiguracao()
    {
        $cache = new FilesystemAdapter();
        $logger = new Logger('test');

        $client = new Client([
            'base_url' => 'https://foo.com',
        ], $logger, $cache);
        $this->assertSame(
            'https://foo.com/sku',
            $client->getResourceUri('/sku')
        );

        return $client;
    }

    /**
     * @depends testUrlBaseadoEmConfiguracao
     *
     * @param mixed $client
     */
    public function testUrlEvitandoConfiguracao($client)
    {
        $url = 'https://bar.com/hi';
        $this->assertSame(
            $url,
            $client->getResourceUri([
                'endpoint' => 'https://bar.com/hi',
            ])
        );

        $this->assertSame($url, $client->getResourceUri(['url' => $url]));

        return $client;
    }

    /**
     * @depends testUrlBaseadoEmConfiguracao
     *
     * @param mixed $client
     */
    public function testAcessoAObjetoRequest($client)
    {
        $this->assertInstanceOf(Request::class, $client->factoryRequest('/'));
    }

    /**
     * @depends testUrlBaseadoEmConfiguracao
     *
     * @param mixed $client
     */
    public function testObjetoRequestPossuiHeader($client)
    {
        $request = $client->factoryRequest('/');
        $headers = $request->getHeader();
        $this->assertIsArray($headers);
        $this->assertArrayHasKey('Accept', $headers);
        $this->assertArrayHasKey('Content-Type', $headers);
    }

    /**
     * @depends testUrlBaseadoEmConfiguracao
     *
     * @param mixed $client
     */
    public function testExecutaRequisiçõesPost($client)
    {
        $proxy = $this->proxy($client);
        $string = 'foo=bar&zeta=jones';
        $array = [
            'foo' => 'bar',
            'zeta' => 'jones',
        ];

        $request = $proxy->factoryPostRequest('/', $string);
        $this->assertSame($string, $request->getBody());

        $request = $proxy->factoryPostRequest('/', $array);
        $this->assertSame($string, $request->getBody());
    }

    public function testCatlemockHelloWorldGet()
    {
        $client = $this->factoryCastlemockClient();
        $response = $client->get('/hello-world');
        $this->assertSame(200, $response->getHttpStatusCode());
        $this->assertSame('runner', $response->getData()->get('blade'));
    }

    public function testCatlemockHelloWorldPost()
    {
        $client = $this->factoryCastlemockClient();
        $response = $client->post('/hello-world', '');
        $this->assertSame(200, $response->getHttpStatusCode());
        $this->assertSame('bar', $response->getData()->get('foo'));
    }

    public function factoryCastlemockClient(): Reflected
    {
        $cache = new FilesystemAdapter();

        $client = new Client([
            'base_url' => $this::CASTLEMOCK_PREFIX,
        ], $this->getLogger(), $cache);

        return $this->proxy($client);
    }

}
