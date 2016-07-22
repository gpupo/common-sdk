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

use Gpupo\CommonSchema\TranslatorDataCollection;

trait TranslatorManagerTrait
{
    public function factoryTranslator(array $data = [])
    {
        throw new \Exception('factoryTranslator() deve ser implementado!');
    }

    protected function factoryTranslatorByNative($entity)
    {
        return $this->factoryTranslator(['native' => $entity]);
    }

    protected function factoryTranslatorByForeign(TranslatorDataCollection $entity)
    {
        return $this->factoryTranslator(['foreign' => $entity]);
    }

    public function translatorUpdate(TranslatorDataCollection $data, TranslatorDataCollection $existent = null)
    {
        $entity = $this->factoryTranslatorByForeign($data)->translateFrom();

        if (!empty($existent)) {
            $previous = $this->factoryTranslatorByForeign($existent)->translateFrom();
        }

        return $this->update($entity, empty($existent) ? null : $previous);
    }

    public function translatorFetch()
    {
        $dataCollection = new TranslatorDataCollection();
        $collection = $this->fetch();

        if (0 < $collection->count()) {
            foreach ($collection as $entity) {
                $dataCollection->add($this->factoryTranslatorByNative($entity)->translateTo());
            }
        }

        return $dataCollection;
    }

    public function translatorFindById($itemId)
    {
        $collection = $this->findById($itemId);

        if (empty($collection)) {
            return false;
        }

        return $this->factoryTranslator(['native' => $collection])->translateTo();
    }
}
