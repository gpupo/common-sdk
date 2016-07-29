<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\Tests\CommonSdk\Traits;

use Gpupo\Common\Entity\CollectionAbstract;

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

        throw new \Exception($className.' not found!', 1);
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
        $getter = 'get'.$this->camelCase($name);

        if ($type === 'object') {
            return $this->assertInstanceOf(CollectionAbstract::class, $object->$getter());
        }

        if ($type === 'datetime') {
            return;
        }

        if (!array_key_exists($name, $expected)) {
            return $this->markSkipped('not found key '.$name);
        }

        $this->assertSame($expected[$name], $object->get($name), 'assert Schema Setter simple');

        $this->assertSame($expected[$name], $object->$getter(), 'assert Schema Setter magical');
    }

    public function assertSchemaSetter($name, $type, $object)
    {
        $case = $this->camelCase($name);
        $setter = 'set'.$case;
        $getter = 'get'.$case;

        if ($type === 'object') {
            //implement
        } elseif ($type === 'datetime') {
            $s = '2016-06-30T13:36:58+00:00';
            $this->assertSame($s, $object->$setter($s)->$getter(), 'assertSchemaSetter');
        } else {
            $this->assertSame('foo', $object->$setter('foo')->$getter(), 'assertSchemaSetter');
        }
    }

}
