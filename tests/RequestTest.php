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

namespace Gpupo\Tests\CommonSdk;

use Gpupo\CommonSdk\Request;

/**
 * @coversDefaultClass \Gpupo\CommonSdk\Request
 */
class RequestTest extends TestCaseAbstract
{
    /**
     * @return \Gpupo\CommonSdk\Request
     */
    public function dataProviderRequest()
    {
        return [[new Request(['status_code' => 200])]];
    }

    /**
     * @testdox ``getBody()``
     * @cover ::getBody
     * @dataProvider dataProviderRequest
     */
    public function testGetBody(Request $request)
    {
        $this->markIncomplete('getBody() need implementation!');
    }

    /**
     * @testdox ``getHeader()``
     * @cover ::getHeader
     * @dataProvider dataProviderRequest
     */
    public function testGetHeader(Request $request)
    {
        $this->markIncomplete('getHeader() need implementation!');
    }

    /**
     * @testdox ``setTransport()``
     * @cover ::setTransport
     * @dataProvider dataProviderRequest
     */
    public function testSetTransport(Request $request)
    {
        $this->markIncomplete('setTransport() need implementation!');
    }

    /**
     * @testdox ``getTransport()``
     * @cover ::getTransport
     * @dataProvider dataProviderRequest
     */
    public function testGetTransport(Request $request)
    {
        $this->markIncomplete('getTransport() need implementation!');
    }

    /**
     * @testdox ``exec()``
     * @cover ::exec
     * @dataProvider dataProviderRequest
     */
    public function testExec(Request $request)
    {
        $this->markIncomplete('exec() need implementation!');
    }
}
