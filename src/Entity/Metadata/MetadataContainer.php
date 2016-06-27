<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\CommonSdk\Entity\Metadata;

use Gpupo\Common\Entity\Collection;

/**
 * @codeCoverageIgnore
 */
final class MetadataContainer extends MetadataContainerAbstract
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
