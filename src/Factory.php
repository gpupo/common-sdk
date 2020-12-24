<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk;

use Gpupo\CommonSdk\Client\Client;
use Gpupo\CommonSdk\Entity\Entity;
use Gpupo\CommonSdk\Entity\GenericManager;

class Factory extends FactoryAbstract
{
    public function setClient(?array $clientOptions = [])
    {
        $this->client = new Client($clientOptions, $this->getLogger(), $this->getSimpleCache());
    }

    public function getNamespace()
    {
        return '\\'.__NAMESPACE__.'\Entity\\';
    }

    public function getSchema(): array
    {
        return [
            'generic' => [
                'manager' => GenericManager::class,
                'class' => Entity::class,
            ],
        ];
    }
}
