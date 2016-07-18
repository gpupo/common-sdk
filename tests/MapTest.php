<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\Tests\CommonSdk;

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
     * @test
     */
    public function getResource(Map $map)
    {
        $this->markIncomplete('getResource() need implementation!');
    }

    /**
     * @testdox ``placeHolderValueEmpty()``
     * @cover ::placeHolderValueEmpty
     * @dataProvider dataProviderMap
     * @test
     */
    public function placeHolderValueEmpty(Map $map)
    {
        $this->markIncomplete('placeHolderValueEmpty() need implementation!');
    }

    /**
     * @testdox ``populatePlaceholders()``
     * @cover ::populatePlaceholders
     * @dataProvider dataProviderMap
     * @test
     */
    public function populatePlaceholders(Map $map)
    {
        $this->markIncomplete('populatePlaceholders() need implementation!');
    }

    /**
     * @testdox ``toLog()``
     * @cover ::toLog
     * @dataProvider dataProviderMap
     * @test
     */
    public function toLog(Map $map)
    {
        $this->markIncomplete('toLog() need implementation!');
    }

    /**
     * @testdox ``getMode()``
     * @cover ::getMode
     * @dataProvider dataProviderMap
     * @test
     */
    public function getMode(Map $map)
    {
        $this->markIncomplete('getMode() need implementation!');
    }
}
