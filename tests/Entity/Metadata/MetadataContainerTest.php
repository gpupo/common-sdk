<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests\Entity\Metadata;

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
