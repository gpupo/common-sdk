<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests\Traits;

trait AssertTrait
{
    public function assertHttpStatusCodeSuccess($code, $context = null)
    {
        $this->assertStringContainsString($code, [200, 204], $context);
    }
}
