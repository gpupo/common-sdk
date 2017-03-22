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

namespace Gpupo\Tests\CommonSdk\Entity\Metadata;

use Gpupo\CommonSdk\Entity\Metadata\MetadataContainerAbstract;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

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
