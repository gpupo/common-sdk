<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Entity;

use Gpupo\Common\Entity\CollectionAbstract as Common;
use Gpupo\Common\Entity\CollectionInterface;

abstract class CollectionAbstract extends Common implements CollectionInterface
{
    public function __construct(array $elements = [])
    {
        $list = [];

        foreach ($elements as $data) {
            $list[] = $this->factoryElement($data);
        }

        parent::__construct($list);
    }

    abstract public function factoryElement($data);

    public function factoryElementAndAdd($data)
    {
        $this->add($this->factoryElement($data));
    }

    public function toLog(): array
    {
        $data = [];

        foreach ($this->all() as $i) {
            $data[] = $i->toLog();
        }

        return $data;
    }
}
