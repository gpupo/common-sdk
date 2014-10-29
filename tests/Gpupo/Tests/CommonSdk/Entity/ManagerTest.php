<?php

namespace Gpupo\Tests\CommonSdk\Entity;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\CommonSdk\Entity\Manager;

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
        $manager = new Manager;
      
        $collection = $factoryCollection->invokeArgs($manager, [['foo' => 'bar']]);
      
        $this->assertEquals('bar', $collection->getFoo());
    }
}
