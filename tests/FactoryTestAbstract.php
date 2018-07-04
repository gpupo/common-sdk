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

        $origin = [
            'client_id' => $factory->getOptions()->get('client_id'),
            'access_token' =>  $factory->getOptions()->get('access_token'),
        ];

        $manager = $factory->factoryManager('generic');

        $objects = [
             $factory,
             $factory->getClient(),
             $manager,
             $manager->getClient(),
        ];

        $this->assertSameOptions($objects, $origin);

        $expected = [
            'client_id' => 'UJDH1112224444',
            'access_token' =>  888838,
        ];

        $ormClient = new ORMClient();
        $ormClient->setClientId($expected['client_id']);
        $accessToken = new AccessToken();
        $accessToken->setAccessToken($expected['access_token']);
        $ormClient->setAccessToken($accessToken);
        $factory->setApplicationAPIClient($ormClient);

        $manager = $factory->factoryManager('generic');
        $objects = [
             $factory,
             $factory->getClient(),
             $manager,
             $manager->getClient(),
        ];

        $this->assertSameOptions($objects, $expected);
    }

    protected function assertSameOptions(array $objects, $expected)
    {
        foreach($objects as $obj) {
            $this->assertSame($expected['client_id'], $obj->getOptions()->get('client_id'), get_class($obj));
            $this->assertSame($expected['access_token'], $obj->getOptions()->get('access_token'), get_class($obj));
        }
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
