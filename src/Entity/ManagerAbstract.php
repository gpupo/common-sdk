<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\CommonSdk\Entity;

use Gpupo\Common\Entity\Collection;
use Gpupo\CommonSdk\Client\ClientManagerAbstract;
use Gpupo\CommonSdk\Exception\ManagerException;
use Gpupo\CommonSdk\Traits\EntityDiffTrait;
use Gpupo\CommonSdk\Traits\FactoryTrait;
use Gpupo\CommonSdk\Traits\MagicCommandTrait;

abstract class ManagerAbstract extends ClientManagerAbstract
{
    use FactoryTrait;
    use EntityDiffTrait;
    use MagicCommandTrait;

    protected $entity;

    protected function magicCommandCallList()
    {
        return ['save'];
    }

    protected function magicSave($suplement, $input)
    {
        return $this->save($input, $suplement);
    }

    public function save(EntityInterface $entity, $route = 'save')
    {
        $existent = $entity->getPrevious() ?: $this->findById($entity->getId());

        if ($existent) {
            return $this->update($entity, $existent);
        }

        return $this->execute($this->factoryMap($route), $entity->toJson($route));
    }

    /**
     * Manager deve implementar sua forma de atualização.
     *
     * <code>
     * //..
     *   if ($this->attributesDiff($existent, $entity)) {
     *   // faça um tipo de ação usando self::execute()
     *   }
     * </code>
     */
    abstract public function update(EntityInterface $entity, EntityInterface $existent);

    public function findById($itemId)
    {
        try {
            $response =  $this->perform($this->factoryMap('findById',
            ['itemId' => $itemId]));

            return $response->getData();
        } catch (ManagerException $exception) {
            return false;
        }
    }

    public function fetch($offset = 0, $limit = 50, array $parameters = [])
    {
        $response =  $this->perform($this->factoryMap('fetch',
            array_merge($parameters, ['offset' => $offset, 'limit' => $limit])));

        return $response->getData();
    }

    protected function factoryCollection(array $list)
    {
        return new Collection($list);
    }

    protected function getEntityName()
    {
        if (empty($this->entity)) {
            throw new ManagerException('Entity missed!');
        }

        return $this->entity;
    }

    protected function factoryEntityCollection(array $data)
    {
        $list = [];
        foreach ($data as $item) {
            if (is_array($item)) {
                $list[] = $this->factoryEntity($item);
            }
        }

        return $this->factoryCollection($list);
    }

    protected function factoryEntity(array $data = null)
    {
        return $this->factoryNeighborObject($this->getEntityName(), $data);
    }
}
