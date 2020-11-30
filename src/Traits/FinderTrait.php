<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Traits;

use Gpupo\CommonSdk\Entity\EntityInterface;

trait FinderTrait
{
    public function findById($id)
    {
        foreach ($this->all() as $item) {
            if (empty($item) || !$item instanceof EntityInterface) {
                continue;
            }

            if ((int) ($item->getId()) === (int) $id) {
                return $item;
            }
        }
    }

    abstract protected function all();
}
