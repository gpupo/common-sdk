<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\CommonSdk\Tests\Traits;

use Gpupo\Common\Entity\CollectionAbstract;
use Gpupo\CommonSdk\Entity\Entity;
use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\CommonSdk\Tests\Entity\MockupData;

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

    public function assertSchemaGetter($name, $type, $object, $expected)
    {
        $getter = 'get'.$this->camelCase($name);

        if ('object' === $type) {
            return $this->assertInstanceOf(CollectionAbstract::class, $object->{$getter}());
        }

        if ('datetime' === $type) {
            return;
        }

        if (!array_key_exists($name, $expected)) {
            return $this->markSkipped('not found key '.$name);
        }

        $this->assertSame($expected[$name], $object->get($name), 'assert Schema Setter simple');

        $this->assertSame($expected[$name], $object->{$getter}(), 'assert Schema Setter magical');
    }

    public function assertSchemaSetter($name, $type, $object)
    {
        $case = $this->camelCase($name);
        $setter = 'set'.$case;
        $getter = 'get'.$case;

        if ('object' === $type) {
            $s = new Entity();
            $this->assertInstanceOf(EntityInterface::class, $object->{$setter}($s)->{$getter}());
        } elseif ('datetime' === $type) {
            $s = '2016-06-30T13:36:58+00:00';
            $this->assertSame($s, $object->{$setter}($s)->{$getter}(), 'assertSchemaSetter');
        } else {
            $this->assertSame('foo', $object->{$setter}('foo')->{$getter}(), 'assertSchemaSetter');
        }
    }

    protected function dataProviderEntitySchema($className, array $data = null)
    {
        if (empty($data)) {
            $data = MockupData::create($className);
        }

        $object = static::createObject($className, $data);

        return [[$object, $data]];
    }

    /**
     * @todo Reutilizar Tool
     *
     * @param mixed $string
     */
    private function camelCase($string)
    {
        return ucfirst($string);
    }
}
