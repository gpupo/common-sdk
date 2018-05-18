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

namespace Gpupo\Tests\CommonSdk\Client;

use Gpupo\CommonSdk\Client\Client;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * @coversNothing
 */
class ClientTest extends TestCaseAbstract
{
    public function testUrlIndependenteDeConfiguracao()
    {
        $client = new Client();
        $this->assertSame(
            '/sku',
            $client->getResourceUri('/sku')
        );
    }

    public function testUrlBaseadoEmConfiguracao()
    {
        $cache = new FilesystemCache();
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
        $this->assertInstanceOf('\Gpupo\CommonSdk\Request', $client->factoryRequest('/'));
    }

    /**
     * @depends testUrlBaseadoEmConfiguracao
     *
     * @param mixed $client
     */
    public function testObjetoRequestPossuiHeader($client)
    {
        $request = $client->factoryRequest('/');

        $this->assertContains('Content-Type: application/json;charset=UTF-8', $request->getHeader());
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
}
