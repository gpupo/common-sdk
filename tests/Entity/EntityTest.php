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

namespace Gpupo\CommonSdk\Tests\Entity;

use Gpupo\CommonSdk\Entity\Entity;
use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\CommonSdk\Tests\TestCaseAbstract;
use Gpupo\CommonSdk\Tests\Traits\EntityTrait;

/**
 * @covers \Gpupo\CommonSdk\Entity\EntityAbstract
 */
class EntityTest extends TestCaseAbstract
{
    use EntityTrait;

    public static function setUpBeforeClass(): void
    {
        static::setFullyQualifiedObject(Entity::class);
        parent::setUpBeforeClass();
    }

    public function dataProviderObject()
    {
        $expected = [
            'key' => 'hello',
            'value' => 1.2,
        ];

        return [[
            new Entity($expected),
            $expected,
        ]];
    }

    public function dataProviderEntityFoo()
    {
        $expected = [
            'Foo_Codigo' => '456',
            'Foo_Descricao' => 'Um dia qualquer',
            'FooBar_QtdeBar' => '7',
            'FooBar_Ideal_ZeT' => '2',
            'Foo_GTIN' => '68999444Zse1',
        ];

        $foo = new EntityFoo($expected);

        return [[
            $foo,
            $expected,
        ]];
    }

    public function testAcessoAIdentificadorPadraoDaEntidade()
    {
        $entity = $this->factory();

        $this->assertSame('hello', $entity->getId());
    }

    public function testAcessoAoNomeDaEntidadeAtual()
    {
        $entity = $this->factory();
        $this->assertSame('Entity', $entity->getCalledEntityName());
        $this->assertSame(Entity::class, $entity->getCalledEntityName(true));
    }

    public function testValidaDadosObrigatórios()
    {
        $this->expectException(\Gpupo\CommonSdk\Exception\SchemaException::class);

        $entity = new Entity(['key' => '']);
        $entity->toJson();
    }

    /**
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testInterfaces(EntityInterface $object, $expected = null)
    {
        $this->assertIsString($object->getKey());
        $this->assertIsFloat($object->getValue());
        $this->assertIsArray($object->toArray());
        $this->assertIsArray($object->toLog());
        $this->assertIsString($object->toJson());
        $this->assertInstanceof('\Gpupo\Common\Entity\CollectionInterface', $object);
        $this->assertInstanceof('\Gpupo\Common\Entity\CollectionInterface', $object);
    }

    /**
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testPossuiGetterParaAcessoAFoo(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('key', 'string', $object, $expected);
    }

    /**
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testPossuiSetterParaDefinirFoo(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('key', 'string', $object);
    }

    /**
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testPossuiGetterParaAcessoABar(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('value', 'number', $object, $expected);
    }

    /**
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testPossuiSetterParaDefinirBar(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('value', 'number', $object);
    }

    /**
     * @dataProvider dataProviderEntityFoo
     *
     * @param null|mixed $expected
     */
    public function testAcessoAChavesForaDoPadrao(EntityInterface $object, $expected = null)
    {
        $this->assertSame((int) $expected['Foo_Codigo'], $object->getFoo_Codigo());
        $this->assertSame($expected['Foo_Descricao'], $object->getFoo_Descricao());
        $this->assertSame((int) $expected['FooBar_QtdeBar'], $object->getFooBar_QtdeBar());
        $this->assertSame((int) $expected['FooBar_Ideal_ZeT'], $object->getFooBar_Ideal_ZeT());
        $this->assertSame($expected['Foo_GTIN'], $object->getFoo_GTIN());
    }

    protected function factory(): EntityInterface
    {
        return new Entity(['key' => 'hello']);
    }
}
