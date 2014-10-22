<?php

namespace Gpupo\Tests\CommonSdk;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\CommonSdk\Client;

class ClientTest extends TestCaseAbstract
{
    public function testInit()
    {
        $client = new Client;
    }
}
