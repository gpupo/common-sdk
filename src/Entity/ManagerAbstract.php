<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Entity;

use Gpupo\Common\Entity\Collection;
use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\Common\Interfaces\OptionsInterface;
use Gpupo\Common\Traits\OptionsTrait;
use Gpupo\CommonSchema\ArrayCollection\Thing\EntityInterface as ThingInterface;
use Gpupo\CommonSchema\ORM\Entity\EntityInterface as ORMEntityInterface;
use Gpupo\CommonSdk\Client\ClientManagerAbstract;
use Gpupo\CommonSdk\Exception\ManagerException;
use Gpupo\CommonSdk\FactoryAbstract;
use Gpupo\CommonSdk\Response;
use Gpupo\CommonSdk\Traits\EntityDiffTrait;
use Gpupo\CommonSdk\Traits\FactoryTrait;
use Gpupo\CommonSdk\Traits\MagicCommandTrait;

abstract class ManagerAbstract extends ClientManagerAbstract implements OptionsInterface
{
    use FactoryTrait;
    use EntityDiffTrait;
    use MagicCommandTrait;
    use OptionsTrait;

    protected $entity;

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
    public function update(EntityInterface $entity, EntityInterface $existent)
    {
    }

    public function findById($itemId): ?CollectionInterface
    {
        try {
            $map = $this->factoryMap('findById', ['itemId' => $itemId]);

            return $this->processResponse($this->perform($map));
        } catch (ManagerException $exception) {
            return null;
        }
    }

    public function rawFetch($offset = 0, $limit = 50, array $parameters = [], $route = 'fetch'): ?Collection
    {
        $pars = array_merge($this->fetchDefaultParameters(), $parameters, ['offset' => $offset, 'limit' => $limit]);
        $response = $this->perform($this->factoryMap($route, $pars));

        return $this->processResponse($response);
    }

    public function fetch(int $offset = 0, int $limit = 50, array $parameters = [], string $route = 'fetch'): ?CollectionInterface
    {
        return $this->fetchPrepare($this->rawFetch($offset, $limit, $parameters, $route));
    }

    public function fetchByRoute($route = 'fetch', $offset = 0, $limit = 50, array $parameters = [])
    {
        return $this->fetch($offset, $limit, $parameters, $route);
    }

    protected function magicCommandCallList()
    {
        return ['save'];
    }

    protected function magicSave($suplement, $input)
    {
        return $this->save($input, $suplement);
    }

    protected function processResponse(Response $response)
    {
        return $response->getData();
    }

    protected function fetchDefaultParameters(): array
    {
        return [];
    }

    /**
     * @param mixed $data
     */
    protected function fetchPrepare($data)
    {
        if (empty($data)) {
            return null;
        }

        return $data;
    }

    protected function factoryCollection(array $list): CollectionInterface
    {
        return new Collection($list);
    }

    protected function getEntityName(): string
    {
        if (empty($this->entity)) {
            throw new ManagerException('Entity missed!');
        }

        return $this->entity;
    }

    protected function factoryEntityCollection($data): CollectionInterface
    {
        $list = [];
        foreach ($data as $item) {
            if (\is_array($item)) {
                $list[] = $this->factoryEntity($item);
            }
        }

        return $this->factoryCollection($list);
    }

    protected function factoryEntity($data): CollectionInterface
    {
        $ent = $this->factoryNeighborObject($this->getEntityName(), $data);
        $ent->set('expands', $data);

        return $ent;
    }

    protected function factorySubManager(FactoryAbstract $factory, $name): ManagerInterface
    {
        $subManager = $factory->factoryManager($name)->setClient($this->getClient());

        if ($this->isDryRun()) {
            $subManager->setDryRun($this->getDryRun());
        }

        return $subManager;
    }

    protected function factoryORM(ThingInterface $thing, string $string): ORMEntityInterface
    {
        $class = sprintf('%s\\%s', $this->getClient()->getOptions()->get('common_schema_namespace'), $string);

        return $thing->toOrm($class);
    }
}
