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

namespace Gpupo\Tests\CommonSdk\Entity\Metadata;

use Gpupo\CommonSdk\Entity\Metadata\Metadata;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

/**
 * @coversDefaultClass \Gpupo\CommonSdk\Entity\Metadata\Metadata
 */
class MetadataTest extends TestCaseAbstract
{
    /**
     * @return \Gpupo\CommonSdk\Entity\Metadata\Metadata
     */
    public function dataProviderMetadata()
    {
        return [[new Metadata()]];
    }

    /**
     * @testdox ``getOffset()``
     * @cover ::getOffset
     * @dataProvider dataProviderMetadata
     */
    public function testGetOffset(Metadata $metadata)
    {
        $this->markIncomplete('getOffset() need implementation!');
    }

    /**
     * @testdox ``getLimit()``
     * @cover ::getLimit
     * @dataProvider dataProviderMetadata
     */
    public function testGetLimit(Metadata $metadata)
    {
        $this->markIncomplete('getLimit() need implementation!');
    }

    /**
     * @testdox ``getTotalRows()``
     * @cover ::getTotalRows
     * @dataProvider dataProviderMetadata
     */
    public function testGetTotalRows(Metadata $metadata)
    {
        $this->markIncomplete('getTotalRows() need implementation!');
    }
}
