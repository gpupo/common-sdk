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

namespace Gpupo\Tests\CommonSdk\Entity;

use Gpupo\CommonSdk\Entity\Entity;
use Gpupo\CommonSdk\Entity\Manager;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

class ManagerTest extends TestCaseAbstract
{
    protected function getMethod($name)
    {
        $class = new \ReflectionClass('\Gpupo\CommonSdk\Entity\Manager');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    public function testFactoryCollection()
    {
        $factoryCollection = $this->getMethod('FactoryCollection');
        $manager = new Manager();

        $collection = $factoryCollection->invokeArgs($manager, [['foo' => 'bar']]);

        $this->assertEquals('bar', $collection->getFoo());
    }

    /**
     * @dataProvider dataProviderEntityData
     */
    public function testNaoEncontraDiferencaEntreEntidadesIguais($dataA)
    {
        $entityA = new Entity($dataA);
        $entityB = new Entity($dataA);

        $manager = new Manager();

        $this->assertFalse($manager->attributesDiff($entityA, $entityB));
    }

    /**
     * @dataProvider dataProviderEntityData
     */
    public function testEncontraDiferencaEntreEntidadesDiferentes($dataA, $dataB)
    {
        $entityA = new Entity($dataA);
        $entityB = new Entity($dataB);

        $manager = new Manager();

        $this->assertEquals(['foo', 'bar'], $manager->attributesDiff($entityA, $entityB));
    }

    /**
     * @dataProvider dataProviderEntityData
     */
    public function testEncontraDiferencaEntreEntidadesDiferentesAPartirDeChavesSelecionadas($dataA, $dataB)
    {
        $entityA = new Entity($dataA);
        $entityB = new Entity($dataB);

        $manager = new Manager();

        foreach (['foo', 'bar'] as $key) {
            $this->assertEquals([$key], $manager->attributesDiff($entityA, $entityB, [$key]));
        }
    }

    /**
     * @dataProvider dataProviderEntityData
     * @expectedException \InvalidArgumentException
     */
    public function testFalhaAoTentarEncontrarDiferencaUsandoPropriedadeInexistente($dataA, $dataB)
    {
        $entityA = new Entity($dataA);
        $entityB = new Entity($dataB);

        $manager = new Manager();

        $manager->attributesDiff($entityA, $entityB, ['noExist']);
    }

    public function dataProviderEntityData()
    {
        return [
            [
                ['foo' => 'hello', 'bar' => 1],
                ['foo' => 'world', 'bar' => 2],
            ],
        ];
    }
}
