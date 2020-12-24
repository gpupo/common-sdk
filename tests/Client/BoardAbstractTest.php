<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests\Client;

use Gpupo\Common\Tools\Reflected;
use Gpupo\CommonSdk\Tests\TestCaseAbstract;
use Monolog\Logger;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @coversNothing
 */
class BoardAbstractTest extends TestCaseAbstract
{
    private $__i = 0;

    public function testFillPlaceholdersWithOptions(): Reflected
    {
        $cache = new FilesystemAdapter();
        $logger = new Logger('test');
        $rawBoard = new Board(['bar' => 'jack'], $logger, $cache);
        $board = $this->proxy($rawBoard);
        $string = $board->fillPlaceholdersWithOptions('foo={bar}', ['bar']);
        $this->assertSame('foo=jack', $string);

        return $board;
    }

    /**
     * @depends testFillPlaceholdersWithOptions
     */
    public function testDestroyCache(Reflected $board)
    {
        $sample = [
            'hello' => 'world',
            'i' => 0,
        ];

        $resourceString = sprintf('resource_%d', rand(0, 99));

        $factoryArrayData = function ($i) use ($sample) {
            return array_merge($sample, [
                'i' => $i,
                'cache_lastmod' => date('H:i:s'),
            ]);
        };

        $cacheAdapter = $board->getSimpleCache();
        $this->assertInstanceOf(CacheInterface::class, $cacheAdapter);

        $factoryItem = function (ItemInterface $item) use ($factoryArrayData) {
            $item->expiresAfter(3600);

            return $factoryArrayData($this->__i);
        };

        $cacheId = $board->simpleCacheGenerateId($resourceString);

        $this->__i = 1;
        $arrayFromCacheItem_Sample_A = $cacheAdapter->get($cacheId, $factoryItem);

        $this->assertIsArray($arrayFromCacheItem_Sample_A);
        $this->assertSame($sample['hello'], $arrayFromCacheItem_Sample_A['hello']);
        $this->assertSame(1, $arrayFromCacheItem_Sample_A['i']);

        $this->assertSame(1, $this->__i, 'Check i');

        $this->__i = 2;
        $arrayFromCacheItem_Sample_B = $cacheAdapter->get($cacheId, $factoryItem);
        $this->assertSame(1, $arrayFromCacheItem_Sample_B['i']);
        $this->assertSame($sample['hello'], $arrayFromCacheItem_Sample_B['hello']);

        $board->destroyCache($resourceString);

        $this->__i = 3;
        $arrayFromCacheItem_Sample_C = $cacheAdapter->get($cacheId, $factoryItem);
        $this->assertSame(3, $this->__i, 'Check i');
        $this->assertSame(3, $arrayFromCacheItem_Sample_C['i']);
    }
}
