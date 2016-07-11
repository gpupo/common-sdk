<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\CommonSdk\Traits;

use Gpupo\CommonSdk\Entity\CollectionAbstract;
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
            'a' => $entityA->$method(),
            'b' => $entityB->$method(),
        ];

        if ($data['a'] instanceof CollectionAbstract) {
            $data['a'] = $data['a']->toJson();
            $data['b'] = $data['b']->toJson();
        }

        $data['isDiff'] = ($data['a'] !== $data['b']);

        return  $data['isDiff'];
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
