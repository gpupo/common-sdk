<?php

/*
 * This file is part of gpupo/cnova-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For more information, see
 * <http://www.g1mr.com/cnova-sdk/>.
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
        return intval($this->get('offset'));
    }

    public function getLimit()
    {
        return intval($this->get('limit'));
    }

    public function getTotalRows()
    {
        return intval($this->get('totalRows'));
    }
}
