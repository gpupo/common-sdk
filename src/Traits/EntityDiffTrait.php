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

namespace Gpupo\CommonSdk\Traits;

use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\CommonSdk\Exception\InvalidArgumentException;

trait EntityDiffTrait
{
    /**
     * Compara duas entidades e indica diferenças.
     *
     * @param EntityInterface $entityA    Entidade principal
     * @param EntityInterface $entityB    Entidade com a qual a principal será comparada
     * @param array           $attributes Lista de propriedades para comparação.
     *                                    Se não informado todos os atributos declarados em getSchema() serão utilizados
     */
    public function attributesDiff(EntityInterface $entityA, EntityInterface $entityB, array $attributes = null)
    {
        $list = [];
        foreach ($this->attributesResolv($entityA, $attributes) as $atribute) {
            if ($this->attributesCompare($entityA, $entityB, $atribute)) {
                $list[] = $atribute;
            }
        }

        if (!empty($list)) {
            return $list;
        }

        return false;
    }

    protected function attributesCompare(EntityInterface $entityA, EntityInterface $entityB, $atribute)
    {
        if (!$entityA->schemaHasKey($atribute)) {
            throw new InvalidArgumentException('Atributo inexistente!');
        }

        $method = 'get'.ucfirst($atribute);

        return ($entityA->$method() !== $entityB->$method());
    }

    protected function attributesResolv(EntityInterface $entityA, array $attributes = null)
    {
        if (empty($attributes)) {
            $list = [];
            foreach ($entityA->getSchema() as $key => $value) {
                if ($value !== 'object') {
                    $list[] = $key;
                }
            }

            return $list;
        }

        return $attributes;
    }
}
