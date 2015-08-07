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
        return self::$fullyQualifiedObject;
    }

    public static function setFullyQualifiedObject($name)
    {
        self::$fullyQualifiedObject = $name;
    }

    public static function createObject($className, array $data = null)
    {
        return new $className($data);
    }

    public static function setUpEntityTest()
    {
        $className = static::getFullyQualifiedObject();

        if (class_exists($className)) {
            self::displayClassDocumentation(static::createObject($className));
        }
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
        $this->assertEquals($expected[$name], $object->get($name));
        $getter = 'get' . $this->camelCase($name);
        $this->assertEquals($expected[$name], $object->$getter());
    }

    public function assertSchemaSetter($name, $type, $object)
    {
        $case = $this->camelCase($name);
        $setter = 'set' . $case;
        $getter = 'get' . $case;
        $this->assertEquals('foo', $object->$setter('foo')->$getter());
    }

    public function testPossuiSchema()
    {
        $className = static::getFullyQualifiedObject();

        if (class_exists($className)) {
            $this->assertInstanceOf($className, self::createObject($className));
        }
    }
}
