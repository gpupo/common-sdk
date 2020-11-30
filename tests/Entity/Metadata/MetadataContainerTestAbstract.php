<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests\Entity\Metadata;

use Gpupo\CommonSdk\Entity\Metadata\MetadataContainerAbstract;
use Gpupo\CommonSdk\Tests\TestCaseAbstract;

abstract class MetadataContainerTestAbstract extends TestCaseAbstract
{
    abstract public function dataProviderContainer();

    /**
     * @dataProvider dataProviderContainer
     */
    public function testÉUmObjetoMetadataContainer(MetadataContainerAbstract $container)
    {
        $this->assertInstanceOf('\Gpupo\CommonSdk\Entity\Metadata\MetadataContainerAbstract', $container);
    }

    /**
     * @dataProvider dataProviderContainer
     */
    public function testPossuiObjetoMetadata(MetadataContainerAbstract $container)
    {
        $this->assertInstanceOf('\Gpupo\CommonSdk\Entity\Metadata\Metadata', $container->getMetadata());
    }

    /**
     * @dataProvider dataProviderContainer
     */
    public function testPossuiPropriedadeIndicadoraDeQuantidadeDeRegistros(MetadataContainerAbstract $container, array $expected)
    {
        $this->assertSame($container->getMetadata()->getTotalRows(), $expected['totalRows']);
    }
}
