<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Entity\Metadata;

use Gpupo\Common\Entity\CollectionAbstract;

/**
 * @method string getFirst()
 * @method string getPrevious()
 * @method string getNext()
 * @method string getLast()
 */
class Metadata extends CollectionAbstract
{
    public function getOffset()
    {
        return (int) ($this->get('offset'));
    }

    public function getLimit()
    {
        return (int) ($this->get('limit'));
    }

    public function getTotalRows()
    {
        return (int) ($this->get('totalRows'));
    }
}
