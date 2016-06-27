<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * For more information, see <http://www.g1mr.com/common-sdk/>.
 */
namespace Gpupo\Tests\CommonSdk\Exception;

use Gpupo\Tests\CommonSdk\TestCaseAbstract;

class MainTest extends TestCaseAbstract
{
    /**
     * @testdox A biblioteca possui uma lista de Exceções
     * @expectedException     Exception
     * @dataProvider         dataProviderList
     */
    public function testList($className)
    {
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
                '\Gpupo\CommonSdk\Exception\\' . $i . 'Exception',
            ];
        }

        return $list;
    }
}
