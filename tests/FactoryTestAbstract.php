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
