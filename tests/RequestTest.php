<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests;

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
