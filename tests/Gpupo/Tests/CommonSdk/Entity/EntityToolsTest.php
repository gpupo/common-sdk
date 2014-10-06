<?php

namespace Gpupo\Tests\CommonSdk\Entity;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\CommonSdk\Entity\EntityTools;

class EntityToolsTest extends TestCaseAbstract
{
    /**
     * @dataProvider dataProviderInformacao
     */
    public function testValidaTiposDeInformacao($value, $type, $expected)
    {
        $this->assertTrue(EntityTools::validate($type, $expected, $type, true));
    }

    /**
     * @dataProvider dataProviderInformacao
     */
    public function testNormalizaTiposDeInformacao($value, $type, $expected)
    {
        $this->assertTrue(EntityTools::normalizeType($value, $type) === $expected);
    }

    /**
     * @expectedException \Gpupo\CommonSdk\Exception\ExceptionInterface
     */
    public function testAbortaComUsoDeDadosInvalidos()
    {
        EntityTools::validate('foo', 'bar', 'integer', true);
    }

    public function testSucessoComUsoDeDadosValidos()
    {
        $this->assertTrue(EntityTools::validate('foo', 3456, 'integer', true));
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
