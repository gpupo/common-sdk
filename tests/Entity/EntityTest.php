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

use Gpupo\CommonSdk\Entity\Entity;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

/**
 * @covers \Gpupo\CommonSdk\Entity\EntityAbstract
 */
class EntityTest extends TestCaseAbstract
{
    protected function factory()
    {
        return new Entity(['foo' => 'hello']);
    }

    public function testAcessoAIdentificadorPadraoDaEntidade()
    {
        $entity = $this->factory();

        $this->assertEquals('hello', $entity->getId());
    }

    public function testAcessoAoNomeDaEntidadeAtual()
    {
        $entity = $this->factory();
        $this->assertEquals('Entity', $entity->getCalledEntityName());
        $this->assertEquals('Gpupo\CommonSdk\Entity\Entity', $entity->getCalledEntityName(true));
    }
}
