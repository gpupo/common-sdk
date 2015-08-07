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

namespace Gpupo\CommonSdk\Entity;

/**
 * Entidade exemplo, utilizada em tests unitários.
 *
 * @codeCoverageIgnore
 */
final class Entity extends EntityAbstract implements EntityInterface
{
    /**
     * {@inheritDoc}
     * Necessário para identificar foo como chave primária, mas se esta
     *  entidade possuísse propriedade [id] isto seria desnecessário.
     *
     * @type string
     */
    protected $primaryKey = 'foo';

    public function getSchema()
    {
        return  [
            'foo'   => 'string',
            'bar'   => 'number',
        ];
    }

    public function setUp()
    {
        $this->setRequiredSchema(['foo']);
    }

}
