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
