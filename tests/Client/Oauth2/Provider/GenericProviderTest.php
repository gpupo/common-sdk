<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\Tests\CommonSdk\Client\Oauth2\Provider;

use Gpupo\CommonSdk\Client\Oauth2\Provider\GenericProvider;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

class GenericProviderTest extends TestCaseAbstract
{
    public function testAcessoAUrlDeAutorizacao()
    {
        $provider = new GenericProvider(
            [
                'clientId'  => 'lambda',
                'authorize' => 'https://foo/bar?client_id={clientId}',
            ]
        );

        $this->assertSame('https://foo/bar?client_id=lambda', $provider->getAuthorizationUrl());
    }
}
