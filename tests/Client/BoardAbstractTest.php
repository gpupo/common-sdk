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

/**
 * @coversNothing
 */
class BoardAbstractTest extends TestCaseAbstract
{
    public function testUrlIndependenteDeConfiguracao()
    {
        $cache = new FilesystemAdapter();
        $logger = new Logger('test');
        $rawBoard = new Board(['bar' => 'jack'], $logger, $cache);
        $board = $this->proxy($rawBoard);
        $string = $board->fillPlaceholdersWithOptions('foo={bar}', ['bar']);
        $this->assertSame('foo=jack', $string);
    }
}
