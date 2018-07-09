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

use Gpupo\Common\Entity\Collection;
use Gpupo\CommonSchema\ORM\Entity\Application\API\OAuth\Client\AccessToken;
use Gpupo\CommonSchema\ORM\Entity\Application\API\OAuth\Client\Client as ORMClient;

abstract class FactoryTestAbstract extends TestCaseAbstract
{
    abstract public function getFactory();

    abstract public function dataProviderObjetos();

    /**
     * @dataProvider dataProviderObjetos
     * @large
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

    /**
     * @large
     */
    public function testSetApplicationAPIClient()
    {
        $factory = $this->getFactory();

        $this->assertSame($factory->getOptions()->get('access_token'), $factory->getClient()->getOptions()->get('access_token'), 'Primal values');

        $origin = [
            'client_id' => $factory->getOptions()->get('client_id'),
            'access_token' => $factory->getOptions()->get('access_token'),
        ];

        // dump($this->getFactory()->getOptions(), $origin, $this->getFactory()->getClient()->getOptions());
        $manager = $factory->factoryManager('generic');

        $objects = [
             $factory,
             $factory->getClient(),
             $manager,
             $manager->getClient(),
        ];

        $this->assertSameOptions($objects, $origin, 'PRE');

        $expected = [
            'client_id' => 'MODIFIED-'.$origin['client_id'],
            'access_token' => 'MODIFIED-'.$origin['access_token'],
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

        $this->assertSameOptions($objects, $expected, 'POS');
    }

    protected function assertSameOptions(array $objects, $expected, $mode = '')
    {
        $i = 0;
        foreach ($objects as $obj) {
            ++$i;

            $options = $obj->getOptions();
            $this->assertInstanceOf(Collection::class, $options);
            $this->assertSame($expected['client_id'], $options->get('client_id'), sprintf('Test#%s %s client_id of %s', $i, $mode, get_class($obj)));
            $this->assertSame($expected['access_token'], $options->get('access_token'), sprintf('Test#%s %s access_token of %s', $i, $mode, get_class($obj)));
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
