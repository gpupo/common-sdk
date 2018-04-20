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

namespace Gpupo\Tests\CommonSdk\Client\Oauth2\Provider;

use Gpupo\CommonSdk\Client\Oauth2\Provider\GenericProvider;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

/**
 * @coversNothing
 */
class GenericProviderTest extends TestCaseAbstract
{
    public function testAcessoAUrlDeAutorizacao()
    {
        $provider = new GenericProvider(
            [
                'clientId' => 'lambda',
                'authorize' => 'https://foo/bar?client_id={clientId}',
            ]
        );

        $this->assertSame('https://foo/bar?client_id=lambda', $provider->getAuthorizationUrl());
    }
}
