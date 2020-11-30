<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests\Exception;

use Gpupo\CommonSdk\Tests\TestCaseAbstract;

/**
 * @coversNothing
 */
class MainTest extends TestCaseAbstract
{
    /**
     * @testdox A biblioteca possui uma lista de Exceções
     * @dataProvider         dataProviderList
     *
     * @param mixed $className
     */
    public function testList($className)
    {
        $this->expectException(\Exception::class);

        $o = new $className('bar');

        $this->assertInstanceOf($className, $o);

        throw $o;
    }

    public function dataProviderList()
    {
        $list = [];

        foreach ([
            'Manager',
            'Runtime',
            'UnexpectedValue',
            'InvalidArgument',
            'Client',
            'Schema',
        ] as $i) {
            $list[] = [
                '\Gpupo\CommonSdk\Exception\\'.$i.'Exception',
            ];
        }

        return $list;
    }
}
