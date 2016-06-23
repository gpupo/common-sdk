<?php

/*
 * This file is part of gpupo/cnova-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For more information, see
 * <http://www.g1mr.com/cnova-sdk/>.
 */
namespace Gpupo\Tests\CommonSdk\Entity\Metadata;

use Gpupo\CommonSdk\Entity\Metadata\MetadataContainer;

class MetadataContainerTest extends MetadataContainerTestAbstract
{
    public function dataProviderContainer()
    {
        $container = new MetadataContainer([
            'metadata'  => [
                ['key' => 'totalRows','value'=> '2'],
                ['key' => 'offset','value'=> '0'],
                ['key' => 'limit','value'=> '50'],
                ['key' => 'first','value'=> '/foo?_offset=0&_limit=50'],
                ['key' => 'nexyt','value'=> ''],
                ['key' => 'last','value'=> '/foo?_offset=0&_limit=50'],
            ]
        ]);

        return [
            [$container, ['totalRows' => 2]],
        ];
    }
}
