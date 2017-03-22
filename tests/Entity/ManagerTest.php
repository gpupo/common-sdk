<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://www.gpupo.com/>.
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

        $this->assertSame('bar', $collection->getFoo());
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

        $this->assertSame(['foo', 'bar'], $manager->attributesDiff($entityA, $entityB));
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
            $this->assertSame([$key], $manager->attributesDiff($entityA, $entityB, [$key]));
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
