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

namespace Gpupo\CommonSdk\Traits;

use Gpupo\Cache\CacheAwareTrait;

trait CacheTrait
{
    use CacheAwareTrait;

    public function initCache($cacheItemPool)
    {
        if (!empty($cacheItemPool)) {
            return $this->setCacheItemPool($cacheItemPool);
        }
    }
}
