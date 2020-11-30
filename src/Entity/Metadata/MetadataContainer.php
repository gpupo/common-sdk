<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Entity\Metadata;

use Gpupo\Common\Entity\Collection;
use Gpupo\Common\Entity\CollectionInterface;

/**
 * @codeCoverageIgnore
 */
final class MetadataContainer extends MetadataContainerAbstract implements CollectionInterface
{
    protected function getKey()
    {
        return 'metadata';
    }

    protected function factoryEntity(array $data)
    {
        return new Collection($data);
    }
}
