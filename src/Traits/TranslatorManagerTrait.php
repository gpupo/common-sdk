<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\CommonSdk\Traits;

use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\CommonSchema\TranslatorDataCollection;

trait TranslatorManagerTrait
{
    public function factoryTranslator(array $data = [])
    {
        throw new \Exception('factoryTranslator() deve ser implementado!');
    }

    public function translatorUpdate(TranslatorDataCollection $data, TranslatorDataCollection $existent = null)
    {
        return $this->update($this->factoryTranslatorByForeign($data)
            ->import(), empty($existent) ? null : $this
            ->factoryTranslatorByForeign($existent)->import());
    }

    public function translatorFetch($offset = 0, $limit = 50, array $parameters = [])
    {
        $dataCollection = new TranslatorDataCollection();
        $collection = $this->fetch($offset, $limit, $parameters);

        if (0 < $collection->count()) {
            foreach ($collection as $entity) {
                $dataCollection->add($this->factoryTranslatorByNative($entity)->export());
            }
        }

        return $dataCollection;
    }

    public function translatorFindById(int $itemId)
    {
        $collection = $this->findById($itemId);

        if (empty($collection)) {
            return false;
        }

        return $this->factoryTranslator(['native' => $collection])->export();
    }

    protected function factoryTranslatorByNative(CollectionInterface $entity)
    {
        return $this->factoryTranslator(['native' => $entity]);
    }

    protected function factoryTranslatorByForeign(TranslatorDataCollection $entity)
    {
        return $this->factoryTranslator(['foreign' => $entity]);
    }
}
