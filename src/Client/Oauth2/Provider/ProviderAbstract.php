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

namespace Gpupo\CommonSdk\Client\Oauth2\Provider;

use Gpupo\CommonSdk\Client\BoardAbstract;

abstract class ProviderAbstract extends BoardAbstract
{
    public $state;

    public function getAuthorizationUrl()
    {
        return $this->fillPlaceholdersWithOptions($this->getOptions()->getAuthorize(), ['clientId']);
    }
}
