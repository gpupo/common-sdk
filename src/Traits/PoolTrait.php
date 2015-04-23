<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\CommonSdk\Traits;

use Gpupo\CommonSdk\Pool;

trait PoolTrait
{
    protected $pool;

    public function getPool()
    {
        if (!$this->pool) {
            $this->pool = new Pool();
        }

        return $this->pool;
    }
}
