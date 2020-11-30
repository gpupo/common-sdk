<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests;

use Gpupo\CommonSdk\Client\Client;
use Gpupo\CommonSdk\Entity\Entity;
use Gpupo\CommonSdk\Entity\GenericManager;
use Gpupo\CommonSdk\Factory;

/**
 * @coversNothing
 */
class FactoryTest extends FactoryTestAbstract
{
    public $namespace = '\Gpupo\CommonSdk\\';

    public function getFactory()
    {
        return Factory::getInstance()->setOptions([
            'client_id' => '987643',
            'client_secret' => 'invisible touch',
            'access_token' => 'TBHS5b3cc535e4b042f9f26ba249',
            'user_id' => 112233,
            'refresh_token' => false,
            'verbose' => true,
            'cacheTTL' => 3600,
            'offset' => 0,
            'limit' => 0,
        ]);
    }

    public function testSimpleInstance()
    {
        $factory = new \Gpupo\CommonSdk\Factory();
        $manager = $factory->factoryManager('generic');
        $this->assertInstanceOf(GenericManager::class, $manager);
    }

    /**
     * Dá acesso a ``Factory``.
     */
    public function testSetClient()
    {
        $factory = new Factory();

        $factory->setClient([
        ]);

        $this->assertInstanceOf(Client::class, $factory->getClient());
    }

    /**
     * @dataProvider dataProviderManager
     *
     * @param mixed $objectExpected
     * @param mixed $target
     */
    public function testCentralizaAcessoAManagers($objectExpected, $target)
    {
        return $this->assertInstanceOf(
            $objectExpected,
            $this->createObject($this->getFactory(), 'factoryManager', $target)
        );
    }

    public function dataProviderObjetos()
    {
        return [
            [Entity::class, 'generic', []],
        ];
    }

    public function dataProviderManager()
    {
        return [
            [GenericManager::class, 'generic'],
        ];
    }
}
