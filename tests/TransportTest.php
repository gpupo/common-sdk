<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests;

use Gpupo\Common\Entity\Collection;
use Gpupo\CommonSdk\Transport;
use Gpupo\CommonSdk\Transport\Driver\CurlDriver;

/**
 * @coversNothing
 */
class TransportTest extends TestCaseAbstract
{
    public function testRecebeObjetoOptions()
    {
        $transport = new Transport(new Collection(['sslVersion' => 'bar']));
        $this->assertInstanceof(CurlDriver::class, $transport);

        return $transport;
    }

    /**
     * @testdox Executa uma requisição para url informada
     * @depends testRecebeObjetoOptions
     */
    public function testExec(Transport $transport)
    {
        $transport->setUrl('https://github.com/');
        $data = $transport->exec();
        $this->assertSame(200, $data['httpStatusCode']);

        return $transport;
    }

    /**
     * @testdox Possui informações sobre a última requisição
     * @depends testExec
     *
     * @param mixed $transport
     */
    public function testLastTransfer($transport)
    {
        $lastTransfer = $transport->getLastTransfer();
        $this->assertInstanceof('\\Gpupo\\Common\\Entity\\Collection', $lastTransfer);
        $this->assertSame('https://github.com/', $lastTransfer->get('url'));
    }
}
