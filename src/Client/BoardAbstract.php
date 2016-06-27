<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\CommonSdk\Client;

use Gpupo\Common\Traits\OptionsTrait;
use Gpupo\Common\Traits\SingletonTrait;
use Gpupo\CommonSdk\Traits\CacheTrait;
use Gpupo\CommonSdk\Traits\LoggerTrait;
use Gpupo\CommonSdk\Traits\PlaceholderTrait;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

abstract class BoardAbstract
{
    use LoggerTrait;
    use CacheTrait;
    use SingletonTrait;
    use OptionsTrait;
    use PlaceholderTrait;

    public function __construct($options = [], LoggerInterface $logger = null, CacheItemPoolInterface $cacheItemPool = null)
    {
        $this->setOptions($options);
        $this->initLogger($logger);
        $this->initCache($cacheItemPool);
    }

    protected function destroyCache($resource)
    {
        if ($this->hasCacheItemPool()) {
            $key = $this->factoryCacheKey($resource);
            $this->getCacheItemPool()->deleteItens([$key]);
        }

        return $this;
    }

    protected function fillPlaceholdersWithOptions($string, array $keys)
    {
        $array = [];
        foreach ($keys as $key) {
            $array[$key] = $this->getOptions()->get($key);
        }

        return $this->fillPlaceholdersWithArray($string, $array);
    }
}
