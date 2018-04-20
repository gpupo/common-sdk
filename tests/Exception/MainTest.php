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

namespace Gpupo\Tests\CommonSdk\Exception;

use Gpupo\Tests\CommonSdk\TestCaseAbstract;

/**
 * @coversNothing
 */
class MainTest extends TestCaseAbstract
{
    /**
     * @testdox A biblioteca possui uma lista de Exceções
     * @dataProvider         dataProviderList
     *
     * @param mixed $className
     */
    public function testList($className)
    {
        $this->expectException(\Exception::class);

        $o = new $className('bar');

        $this->assertInstanceOf($className, $o);

        throw $o;
    }

    public function dataProviderList()
    {
        $list = [];

        foreach ([
            'Manager',
            'Runtime',
            'UnexpectedValue',
            'InvalidArgument',
            'Client',
            'Schema',
        ] as $i) {
            $list[] = [
                '\Gpupo\CommonSdk\Exception\\'.$i.'Exception',
            ];
        }

        return $list;
    }
}
