<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For more information, see
 * <http://www.g1mr.com/common-sdk/>.
 */

namespace Gpupo\Tests\CommonSdk\Client;

use Gpupo\CommonSdk\Client\Client;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

class ClientTest extends TestCaseAbstract
{
    public function testUrlIndependenteDeConfiguracao()
    {
        $client = new Client();
        $this->assertEquals('/sku',
        $client->getResourceUri('/sku'));
    }

    public function testUrlBaseadoEmConfiguracao()
    {
        $client = new Client([
            'base_url'      => 'https://foo.com',
        ]);
        $this->assertEquals('https://foo.com/sku',
        $client->getResourceUri('/sku'));

        return $client;
    }

    /**
     * @depends testUrlBaseadoEmConfiguracao
     */
    public function testUrlEvitandoConfiguracao($client)
    {
        $url = 'https://bar.com/hi';
        $this->assertEquals($url,
            $client->getResourceUri([
                'endpoint'  => 'https://bar.com/hi',
            ]));

        $this->assertEquals($url, $client->getResourceUri(['url' => $url]));

        return $client;
    }

    /**
     * @depends testUrlBaseadoEmConfiguracao
     */
    public function testAcessoAObjetoRequest($client)
    {
        $this->assertInstanceOf('\Gpupo\CommonSdk\Request', $client->factoryRequest('/'));
    }

    /**
     * @depends testUrlBaseadoEmConfiguracao
     */
    public function testObjetoRequestPossuiHeader($client)
    {
        $request = $client->factoryRequest('/');

        $this->assertContains('Content-Type: application/json;charset=UTF-8', $request->getHeader());
    }

    /**
     * @depends testUrlBaseadoEmConfiguracao
     */
    public function testExecutaRequisiçõesPost($client)
    {
        $proxy = $this->proxy($client);
        $string = 'foo=bar&zeta=jones';
        $array = [
            'foo'   => 'bar',
            'zeta'  => 'jones',
        ];

        $request = $proxy->factoryPostRequest('/', $string);
        $this->assertEquals($string, $request->getBody());

        $request = $proxy->factoryPostRequest('/', $array);
        $this->assertEquals($string, $request->getBody());
    }
}
