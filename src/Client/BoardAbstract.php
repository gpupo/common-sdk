<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\CommonSdk\Client;

use Gpupo\Common\Tools\Cache\SimpleCacheAwareTrait;
use Gpupo\Common\Traits\OptionsTrait;
use Gpupo\Common\Traits\SingletonTrait;
use Gpupo\CommonSdk\Traits\LoggerTrait;
use Gpupo\CommonSdk\Traits\PlaceholderTrait;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

abstract class BoardAbstract
{
    use LoggerTrait;
    use SimpleCacheAwareTrait;
    use SingletonTrait;
    use OptionsTrait;
    use PlaceholderTrait;

    public function __construct($options = [], LoggerInterface $logger = null, CacheInterface $cache = null)
    {
        $this->setOptions($options);
        $this->initLogger($logger);
        $this->initSimpleCache($cache);
    }

    protected function destroyCache($resource)
    {
        if ($this->hasSimpleCache()) {
            $key = $this->simpleCacheGenerateId($resource);
            $this->getSimpleCache()->delete($key);
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
