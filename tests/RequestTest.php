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
        return [[new Request(['status_code' => 200, 'body' => 'bar=foo'])]];
    }

    /**
     * @testdox ``getBody()``
     * @cover ::getBody
     * @dataProvider dataProviderRequest
     */
    public function testGetBody(Request $request)
    {
        $this->assertSame('bar=foo', $request->getBody());
    }
}
