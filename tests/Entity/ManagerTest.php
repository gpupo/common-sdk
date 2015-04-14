<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\CommonSdk\Entity;

use Gpupo\CommonSdk\Entity\Manager;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

class ManagerTest extends TestCaseAbstract
{
    protected function getMethod($name)
    {
        $class = new \ReflectionClass('\Gpupo\CommonSdk\Entity\Manager');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    public function testFactoryCollection()
    {
        $factoryCollection = $this->getMethod('FactoryCollection');
        $manager = new Manager();

        $collection = $factoryCollection->invokeArgs($manager, [['foo' => 'bar']]);

        $this->assertEquals('bar', $collection->getFoo());
    }
}
