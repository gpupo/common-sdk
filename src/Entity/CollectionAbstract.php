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

namespace Gpupo\CommonSdk\Entity;

use Gpupo\Common\Entity\CollectionAbstract as Common;

abstract class CollectionAbstract extends Common
{
    abstract public function factoryElement($data);

    public function __construct(array $elements = [])
    {
        $list = [];

        foreach ($elements as $data) {
            $list[] = $this->factoryElement($data);
        }

        parent::__construct($list);
    }
}
