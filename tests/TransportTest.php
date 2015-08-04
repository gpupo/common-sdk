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

namespace Gpupo\Tests\CommonSdk;

use Gpupo\Common\Entity\Collection;
use Gpupo\CommonSdk\Transport;

class TransportTest extends TestCaseAbstract
{
    public function testRecebeObjetoOptions()
    {
        $transport = new Transport(new Collection([]));

        return $transport;
    }

    /**
     * @depends testRecebeObjetoOptions
     */
    public function testExecutaRequisiçãoAUmaUrlInformada(Transport $transport)
    {
        $transport->setUrl('https://github.com/');
        $data = $transport->exec();
        $this->assertEquals(200, $data['httpStatusCode']);

        return $transport;
    }

    /**
     * @depends testExecutaRequisiçãoAUmaUrlInformada
     */
    public function testPossuiInformaçõesSobreAÚltimaRequisição($transport)
    {
        $lastTransfer = $transport->getLastTransfer();
        $this->assertInstanceof("\Gpupo\Common\Entity\Collection", $lastTransfer);
        $this->assertEquals('https://github.com/', $lastTransfer->get('url'));
    }
}
