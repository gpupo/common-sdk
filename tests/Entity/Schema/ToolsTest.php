<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests\Entity\Schema;

use Gpupo\CommonSdk\Entity\Schema\Tools;
use Gpupo\CommonSdk\Tests\TestCaseAbstract;

/**
 * @coversNothing
 */
class ToolsTest extends TestCaseAbstract
{
    /**
     * @dataProvider dataProviderInformacao
     *
     * @param mixed $value
     * @param mixed $type
     * @param mixed $expected
     */
    public function testValidaTiposDeInformacao($value, $type, $expected)
    {
        $this->assertTrue(Tools::validate($type, $expected, $type, true));
    }

    /**
     * @dataProvider dataProviderInformacao
     *
     * @param mixed $value
     * @param mixed $type
     * @param mixed $expected
     */
    public function testNormalizaTiposDeInformacao($value, $type, $expected)
    {
        $this->assertSame($expected, Tools::normalizeType($value, $type));
    }

    public function testAbortaComUsoDeDadosInvalidos()
    {
        $this->expectException(\Gpupo\CommonSdk\Exception\ExceptionInterface::class);

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

    public function testIdentifyObjectType()
    {
        foreach ([
            'object',
            'manyToOne',
            'manyToMany',
            'oneToOne',
            'oneToOneBidirectional',
            'oneToOneUnidirectional',
            'oneToOneSelfReferencing',
            'oneToMany',
        ] as $value) {
            $this->assertTrue(Tools::isObjectType($value));
        }

        $this->assertFalse(Tools::isObjectType('string'));
        $this->assertFalse(Tools::isObjectType('array'));
        $this->assertFalse(Tools::isObjectType('int'));
        $this->assertFalse(Tools::isObjectType('bool'));
    }
}
