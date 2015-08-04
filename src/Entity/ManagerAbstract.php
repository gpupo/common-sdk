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

use Gpupo\Common\Entity\Collection;
use Gpupo\CommonSdk\Client\ClientManagerAbstract;
use Gpupo\CommonSdk\Exception\ManagerException;
use Gpupo\CommonSdk\Response;
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
        $existent = $entity->getPrevious();

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

    protected function processResponse(Response $response)
    {
        return $response->getData();
    }

    public function findById($itemId)
    {
        try {
            $map = $this->factoryMap('findById', ['itemId' => $itemId]);

            return $this->processResponse($this->perform($map));
        } catch (ManagerException $exception) {
            return false;
        }
    }

    protected function fetchDefaultParameters()
    {
        return [];
    }

    /**
     * @return Gpupo\Common\Entity\CollectionAbstract|null
     */
    protected function fetchPrepare($data)
    {
        if (empty($data)) {
            return;
        }

        return $data;
    }

    /**
     * @return Gpupo\Common\Entity\Collection|null
     */
    public function fetch($offset = 0, $limit = 50, array $parameters = [])
    {
        $pars = array_merge($this->fetchDefaultParameters(), $parameters, ['offset' => $offset, 'limit' => $limit]);

        $response = $this->perform($this->factoryMap('fetch', $pars));

        return $this->fetchPrepare($this->processResponse($response));
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

    protected function factoryEntityCollection($data)
    {
        $list = [];
        foreach ($data as $item) {
            if (is_array($item)) {
                $list[] = $this->factoryEntity($item);
            }
        }

        return $this->factoryCollection($list);
    }

    protected function factoryEntity($data = null)
    {
        return $this->factoryNeighborObject($this->getEntityName(), $data);
    }
}
