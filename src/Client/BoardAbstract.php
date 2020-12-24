<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Client;

use Gpupo\Common\Tools\Cache\SimpleCacheAwareTrait;
use Gpupo\Common\Traits\OptionsTrait;
use Gpupo\Common\Traits\SingletonTrait;
use Gpupo\CommonSdk\Traits\LoggerTrait;
use Gpupo\CommonSdk\Traits\PlaceholderTrait;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

abstract class BoardAbstract
{
    use LoggerTrait;
    use OptionsTrait;
    use PlaceholderTrait;
    use SimpleCacheAwareTrait;
    use SingletonTrait;

    public function __construct($options = [], LoggerInterface $logger = null, CacheInterface $cache = null)
    {
        $this->setOptions($options);
        $this->initLogger($logger);
        $this->initSimpleCache($cache);
    }

    protected function destroyCache(string $resource)
    {
        if ($this->hasSimpleCache()) {
            $key = $this->simpleCacheGenerateId($resource);
            $this->getSimpleCache()->deleteItem($key);

            $this->log('debug', 'Destroy Cache', [
                'cacheId' => $key,
            ]);
        }

        return $this;
    }

    protected function fillPlaceholdersWithOptions(string $string, array $keys)
    {
        $array = [];
        foreach ($keys as $key) {
            $array[$key] = $this->getOptions()->get($key);
        }

        return $this->fillPlaceholdersWithArray($string, $array);
    }
}
