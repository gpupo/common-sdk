<?php

namespace Gpupo\Tests\CommonSdk\Entity;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\CommonSdk\Entity\EntityTools;

class EntityToolsTest extends TestCaseAbstract
{

    /**
     * @dataProvider dataProviderInformacao
     */
    public function testNormalizaTiposDeInformacao($value, $type, $expected)
    {
        $this->assertTrue(EntityTools::normalizeType($value, $type) === $expected);
    }

    public function dataProviderInformacao()
    {
        return [
            ['1', 'string', '1'],
            ['1', 'integer', 1],
            ['1.8', 'number', 1.8],
            ['1.81', 'float', 1.81],
        ];
    }
}
