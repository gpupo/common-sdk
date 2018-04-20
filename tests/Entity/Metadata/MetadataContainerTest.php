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

use Gpupo\CommonSdk\Entity\Metadata\MetadataContainer;

/**
 * @coversNothing
 */
class MetadataContainerTest extends MetadataContainerTestAbstract
{
    public function dataProviderContainer()
    {
        $container = new MetadataContainer([
            'metadata' => [
                ['key' => 'totalRows', 'value' => '2'],
                ['key' => 'offset', 'value' => '0'],
                ['key' => 'limit', 'value' => '50'],
                ['key' => 'first', 'value' => '/foo?_offset=0&_limit=50'],
                ['key' => 'nexyt', 'value' => ''],
                ['key' => 'last', 'value' => '/foo?_offset=0&_limit=50'],
            ],
        ]);

        return [
            [$container, ['totalRows' => 2]],
        ];
    }
}
