<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests;

use Gpupo\CommonSdk\Response;

/**
 * @coversDefaultClass \Gpupo\CommonSdk\Response
 */
class ResponseTest extends TestCaseAbstract
{
    /**
     * @return \Gpupo\CommonSdk\Response
     */
    public function dataProviderResponse()
    {
        return [[new Response([
            'responseRaw' => '{"foo":"bar"}',
        ])]];
    }

    /**
     * @testdox ``getData()``
     * @cover ::getData
     * @dataProvider dataProviderResponse
     */
    public function testGetData(Response $response)
    {
        $this->assertSame(['foo' => 'bar'], $response->getData()->toArray());
    }
}
