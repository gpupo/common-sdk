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

namespace Gpupo\Tests\CommonSdk\Entity\Schema;

use Gpupo\CommonSdk\Entity\Schema\Tools;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

class ToolsTest extends TestCaseAbstract
{
    /**
     * @dataProvider dataProviderInformacao
     */
    public function testValidaTiposDeInformacao($value, $type, $expected)
    {
        $this->assertTrue(Tools::validate($type, $expected, $type, true));
    }

    /**
     * @dataProvider dataProviderInformacao
     */
    public function testNormalizaTiposDeInformacao($value, $type, $expected)
    {
        $this->assertTrue(Tools::normalizeType($value, $type) === $expected);
    }

    /**
     * @expectedException \Gpupo\CommonSdk\Exception\ExceptionInterface
     */
    public function testAbortaComUsoDeDadosInvalidos()
    {
        Tools::validate('foo', 'bar', 'integer', true);
    }

    public function testSucessoComUsoDeDadosValidos()
    {
        $this->assertTrue(Tools::validate('foo', 3456, 'integer', true));
    }

    public function dataProviderInformacao()
    {
        return [
            ['hello', 'string', 'hello'],
            ['1', 'integer', 1],
            ['1.8', 'number', 1.8],
            ['1.81', 'float', 1.81],
        ];
    }
}
