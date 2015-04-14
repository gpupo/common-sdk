<?php

/*
 * This file is part of common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\CommonSdk\Client;

use Gpupo\CommonSdk\Client\Client;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

class ClientTest extends TestCaseAbstract
{
    public function testAUrlIndependenteDeConfiguracao()
    {
        $client = new Client();
        $this->assertEquals('/sku',
        $client->getResourceUri('/sku'));
    }
    
    public function testAUrlBaseadoEmConfiguracao()
    {
        $client = new Client([
            'base_url'      => 'https://foo.com',
        ]);
        $this->assertEquals('https://foo.com/sku',
        $client->getResourceUri('/sku'));
        
        return $client;
    }
    
    /**
     * @depends testAUrlBaseadoEmConfiguracao
     */
    public function testAUrlEvitandoConfiguracao($client)
    {
        $url = 'https://bar.com/hi';
        $this->assertEquals($url,
            $client->getResourceUri([
                'endpoint'  => 'https://bar.com/hi',
            ]));
        
        $this->assertEquals($url, $client->getResourceUri(['url' => $url]));
    }
}
