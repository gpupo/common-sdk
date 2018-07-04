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

namespace Gpupo\Tests\CommonSdk;

use Gpupo\CommonSchema\ORM\Entity\Application\API\OAuth\Client\AccessToken;
use Gpupo\CommonSchema\ORM\Entity\Application\API\OAuth\Client\Client as ORMClient;

abstract class FactoryTestAbstract extends TestCaseAbstract
{
    abstract public function getFactory();

    abstract public function dataProviderObjetos();

    /**
     * @dataProvider dataProviderObjetos
     *
     * @param mixed $objectExpected
     * @param mixed $name
     */
    public function testCentralizaCriacaoDeObjetos($objectExpected, $name, array $data = null)
    {
        if (null === $objectExpected) {
            return $this->markTestIncomplete();
        }

        $method = 'create'.ucfirst($name);

        return $this->assertFactoryWorks($objectExpected, $this->getFactory(), $method, $data);
    }

    public function testSetApplicationAPIClient()
    {
        $factory = $this->getFactory();
        $current_client_id = $factory->getOptions()->get('client_id');
        $current_token = $factory->getOptions()->get('access_token');
        $this->assertSame($current_client_id, $factory->getClient()->getOptions()->get('client_id'));
        $this->assertSame($current_token, $factory->getClient()->getOptions()->get('access_token'));
        $ormClient = new ORMClient();
        $ormClient->setClientId(777);
        $accessToken = new AccessToken();
        $accessToken->setAccessToken('bar');
        $ormClient->setAccessToken($accessToken);
        $factory->setApplicationAPIClient($ormClient);

        $this->assertSame(777, $factory->getOptions()->get('client_id'), 'factory client id');
        $this->assertSame('bar', $factory->getOptions()->get('access_token'), 'factory token');
        $this->assertSame('bar', $factory->getClient()->getOptions()->get('access_token'), 'client token');
    }

    protected function createObject($factory, $method, $data = null)
    {
        return $factory->{$method}($data);
    }

    protected function assertFactoryWorks($objectExpected, $factory, $method, array $data = null)
    {
        return $this->assertInstanceOf(
            $objectExpected,
            $this->createObject($factory, $method, $data)
        );
    }
}
