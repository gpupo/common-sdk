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

class EntityTest extends TestCaseAbstract
{
    public function testAcessoAIdentificadorPadraoDaEntidade()
    {
        $entity = new Entity(['foo' => 'hello']);

        $this->assertEquals('hello', $entity->getId());
    }
}
