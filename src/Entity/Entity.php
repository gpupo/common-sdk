<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
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
     * {@inheritdoc}
     * Necessário para identificar key como chave primária, mas se esta
     *  entidade possuísse propriedade [id] isto seria desnecessário.
     *
     * @var string
     */
    protected $primaryKey = 'key';

    public function setUp()
    {
        $this->setRequiredSchema(['key']);
    }

    public function getSchema(): array
    {
        return [
            'key' => 'string',
            'value' => 'string',
        ];
    }
}
