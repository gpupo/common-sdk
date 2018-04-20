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

namespace Gpupo\Tests\CommonSdk\Entity;

use Gpupo\CommonSdk\Entity\Entity;
use Gpupo\CommonSdk\Entity\Manager;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

/**
 * @coversNothing
 */
class ManagerTest extends TestCaseAbstract
{
    public function testFactoryCollection()
    {
        $factoryCollection = $this->getMethod('FactoryCollection');
        $manager = new Manager();

        $collection = $factoryCollection->invokeArgs($manager, [['foo' => 'bar']]);

        $this->assertSame('bar', $collection->getFoo());
    }

    /**
     * @dataProvider dataProviderEntityData
     *
     * @param mixed $dataA
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
     *
     * @param mixed $dataA
     * @param mixed $dataB
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
     *
     * @param mixed $dataA
     * @param mixed $dataB
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
     *
     * @param mixed $dataA
     * @param mixed $dataB
     */
    public function testFalhaAoTentarEncontrarDiferencaUsandoPropriedadeInexistente($dataA, $dataB)
    {
        $this->expectException(\InvalidArgumentException::class);

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

    protected function getMethod($name)
    {
        $class = new \ReflectionClass('\Gpupo\CommonSdk\Entity\Manager');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
