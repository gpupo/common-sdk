<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests;

use Gpupo\CommonSdk\Map;

/**
 * @coversDefaultClass \Gpupo\CommonSdk\Map
 */
class MapTest extends TestCaseAbstract
{
    /**
     * @return \Gpupo\CommonSdk\Map
     */
    public function dataProviderMap()
    {
        return [[new Map(['GET', '/foo'])]];
    }

    /**
     * @testdox ``getResource()``
     * @cover ::getResource
     * @dataProvider dataProviderMap
     */
    public function testGetResource(Map $map)
    {
        $this->assertSame('/foo', $map->getResource());
    }

    /**
     * @testdox ``getMode()``
     * @cover ::getMode
     * @dataProvider dataProviderMap
     */
    public function testGetMode(Map $map)
    {
        $this->assertNull($map->getMode());
    }
}
