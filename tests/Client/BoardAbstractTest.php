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

namespace Gpupo\CommonSdk\Tests\Client;

use Gpupo\CommonSdk\Client\Client;
use Gpupo\CommonSdk\Request;
use Gpupo\CommonSdk\Tests\TestCaseAbstract;
use Monolog\Logger;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Gpupo\Common\Tools\Reflected;
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
            'i'  => 0,
        ];

        $resourceString = 'resource01';

        $factory = function($i) use ($sample) {
            $list = $sample;
            $list['i'] = $i;
            $list['cache_lastmod'] = date("H:i:s");

            return $list;
        };

        $adapter = $board->getSimpleCache();
        $this->assertInstanceOf(CacheInterface::class, $adapter);

        $lambda = function (ItemInterface $item) use ($factory) {
            $item->expiresAfter(3600);

            return $factory($this->__i);
        };

        $cacheId = $board->simpleCacheGenerateId($resourceString);

        $this->__i = 1;
        $listA = $adapter->get($cacheId, $lambda);

        $this->assertIsArray($listA);
        $this->assertSame($sample['hello'], $listA['hello']);
        $this->assertSame(1, $listA['i']);

        $this->assertSame(1, $this->__i, 'Check i');

        $this->__i = 2;
        $listB = $adapter->get($cacheId, $lambda);
        $this->assertSame(1, $listB['i']);
        $this->assertSame($sample['hello'], $listB['hello']);


        $board->destroyCache($resourceString);

        $this->__i = 3;
        $listC = $adapter->get($cacheId, $lambda);
        $this->assertSame(3, $this->__i, 'Check i');
        $this->assertSame(3, $listC['i']);
    }
}
