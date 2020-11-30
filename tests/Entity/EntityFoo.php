<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests\Entity;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class EntityFoo extends EntityAbstract implements EntityInterface
{
    public function getSchema(): array
    {
        return  [
            'Foo_Codigo' => 'integer',
            'Foo_Descricao' => 'string',
            'FooBar_QtdeBar' => 'integer',
            'FooBar_Ideal_ZeT' => 'integer',
            'Foo_GTIN' => 'string',
        ];
    }
}
