<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Traits;

use Gpupo\CommonSdk\Entity\CollectionAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\CommonSdk\Entity\Schema\Tools;
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
    public function attributesDiff(EntityInterface $entityA, EntityInterface $entityB = null, array $attributes = null)
    {
        if (empty($entityB)) {
            return $this->attributesResolv($entityA, $attributes);
        }

        $list = [];
        foreach ($this->attributesResolv($entityA, $attributes) as $atribute) {
            if (true === $this->attributesCompare($entityA, $entityB, $atribute)) {
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

        $data = [
            'a' => $entityA->{$method}(),
            'b' => $entityB->{$method}(),
        ];

        if ($data['a'] instanceof CollectionAbstract) {
            $data['a'] = $data['a']->toJson();
            $data['b'] = $data['b']->toJson();
        }

        $data['isDiff'] = ($data['a'] !== $data['b']);

        return $data['isDiff'];
    }

    protected function attributesResolv(EntityInterface $entityA, array $attributes = null)
    {
        if (empty($attributes)) {
            $list = [];
            foreach ($entityA->getSchema() as $key => $value) {
                if (false === Tools::isObjectType($value)) {
                    $list[] = $key;
                }
            }

            return $list;
        }

        return $attributes;
    }
}
