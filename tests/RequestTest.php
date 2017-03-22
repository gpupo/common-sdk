<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://www.gpupo.com/>.
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
     * @test
     */
    public function getBody(Request $request)
    {
        $this->markIncomplete('getBody() need implementation!');
    }

    /**
     * @testdox ``getHeader()``
     * @cover ::getHeader
     * @dataProvider dataProviderRequest
     * @test
     */
    public function getHeader(Request $request)
    {
        $this->markIncomplete('getHeader() need implementation!');
    }

    /**
     * @testdox ``setTransport()``
     * @cover ::setTransport
     * @dataProvider dataProviderRequest
     * @test
     */
    public function setTransport(Request $request)
    {
        $this->markIncomplete('setTransport() need implementation!');
    }

    /**
     * @testdox ``getTransport()``
     * @cover ::getTransport
     * @dataProvider dataProviderRequest
     * @test
     */
    public function getTransport(Request $request)
    {
        $this->markIncomplete('getTransport() need implementation!');
    }

    /**
     * @testdox ``exec()``
     * @cover ::exec
     * @dataProvider dataProviderRequest
     * @test
     */
    public function exec(Request $request)
    {
        $this->markIncomplete('exec() need implementation!');
    }
}
