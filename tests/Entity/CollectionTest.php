<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests\Entity;

use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\CommonSdk\Entity\Collection;
use Gpupo\CommonSdk\Entity\Entity;
use Gpupo\CommonSdk\Tests\TestCaseAbstract;

/**
 * @covers \Gpupo\CommonSdk\Entity\CollectionAbstract
 */
class CollectionTest extends TestCaseAbstract
{
    public function dataProviderObject()
    {
        $expected = [
            'key' => 'foo',
            'value' => 'bar',
        ];

        return [
            [new Collection([$expected]), $expected],
        ];
    }

    /**
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testPossuiSetterParaDefinirBar(CollectionInterface $object, array $expected)
    {
        $this->assertInstanceof(Entity::class, $object->first());
    }
}
