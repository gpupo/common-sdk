<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For more information, see
 * <http://www.g1mr.com/common-sdk/>.
 */

namespace Gpupo\Tests\CommonSdk\Traits;

trait EntityTrait
{
    private static $fullyQualifiedObject;

    public static function getFullyQualifiedObject()
    {
        if (!empty(self::$fullyQualifiedObject)) {
            return self::$fullyQualifiedObject;
        }
    }

    public static function setFullyQualifiedObject($name)
    {
        self::$fullyQualifiedObject = $name;
        self::setUpEntityTest();
    }

    public static function createObject($className, array $data = null)
    {
        return new $className($data);
    }

    public static function factoryFullyQualifiedObject(array $data = null)
    {
        $className = static::getFullyQualifiedObject();

        if (class_exists($className)) {
            return self::createObject($className, $data);
        }

        throw new \Exception($className." not found!", 1);
    }

    public static function setUpEntityTest()
    {
        $object = self::factoryFullyQualifiedObject();
        if ($object) {
            self::displayClassDocumentation($object);
        }
    }

    protected function dataProviderEntitySchema($className, array $data = null)
    {
        return [[
            static::createObject($className, $data),
            $data,
        ]];
    }

    /**
     * @todo Reutilizar Tool
     */
    private function camelCase($string)
    {
        return ucfirst($string);
    }

    public function assertSchemaGetter($name, $type, $object, $expected)
    {
        $getter = 'get' . $this->camelCase($name);

        if ($type === 'object') {
            return $this->assertInstanceOf('\Gpupo\Common\Entity\CollectionAbstract', $object->$getter());
        }

        if (!array_key_exists($name, $expected)) {
            return $this->markSkipped('not found key '.$name);
        }

        $this->assertEquals($expected[$name], $object->get($name));

        $this->assertEquals($expected[$name], $object->$getter());
    }

    public function assertSchemaSetter($name, $type, $object)
    {
        $case = $this->camelCase($name);
        $setter = 'set' . $case;
        $getter = 'get' . $case;

        if ($type !== 'object') {
            $this->assertEquals('foo', $object->$setter('foo')->$getter());
        }
    }

    /**
     * @testdox Entidade é uma Coleção
     * @dataProvider dataProviderObject
     * @test
     */
    public function entityObject($object, $expected = null)
    {
        return $this->assertInstanceOf('\Gpupo\Common\Entity\CollectionAbstract', $object);
    }
}
