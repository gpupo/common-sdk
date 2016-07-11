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

namespace Gpupo\CommonSdk\Entity;

use Gpupo\Common\Entity\Collection;
use Gpupo\CommonSdk\Client\ClientManagerAbstract;
use Gpupo\CommonSdk\Exception\ManagerException;
use Gpupo\CommonSdk\Response;
use Gpupo\CommonSdk\Traits\EntityDiffTrait;
use Gpupo\CommonSdk\Traits\FactoryTrait;
use Gpupo\CommonSdk\Traits\MagicCommandTrait;
use Gpupo\CommonSdk\FactoryAbstract;

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
     * @return Gpupo\Common\Entity\CollectionAbstract|null|false
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
    public function fetch($offset = 0, $limit = 50, array $parameters = [], $route = 'fetch')
    {
        $pars = array_merge($this->fetchDefaultParameters(), $parameters, ['offset' => $offset, 'limit' => $limit]);

        $response = $this->perform($this->factoryMap($route, $pars));

        return $this->fetchPrepare($this->processResponse($response));
    }

    public function fetchByRoute($route = 'fetch', $offset = 0, $limit = 50, array $parameters = [])
    {
        return $this->fetch($offset, $limit, $parameters, $route);
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

    protected function factorySubManager(FactoryAbstract $factory, $name)
    {
        $subManager = $factory->factoryManager($name)->setClient($this->getClient());

        if ($this->isDryRun()) {
            $subManager->setDryRun($this->getDryRun());
        }

        return $subManager;
    }

}
