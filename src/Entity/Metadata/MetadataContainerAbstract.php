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

namespace Gpupo\CommonSdk\Entity\Metadata;

use Gpupo\Common\Entity\CollectionAbstract;

abstract class MetadataContainerAbstract extends CollectionAbstract
{
    protected $Metadata;

    protected $raw;

    public function __construct($data = null)
    {
        $this->raw = $data;

        $this->factoryMetadata($data);

        $list = $this->dataPiece($this->getKey(), $data);

        if (!empty($list)) {
            foreach ($list as $entityData) {
                $this->add($this->factoryEntity($entityData));
            }
        }
    }

    public function getMetadata()
    {
        return $this->Metadata;
    }

    public function getRaw()
    {
        return $this->raw;
    }

    abstract protected function getKey();

    abstract protected function factoryEntity(array $data);

    protected function normalizeMetas($metas)
    {
        $data = [];

        if (\is_array($metas)) {
            foreach ($metas as $meta) {
                $data[$meta['key']] = $meta['value'];
            }
        }

        return $data;
    }

    protected function dataPiece($piece, $data)
    {
        if ($data instanceof CollectionAbstract) {
            return $data->get($piece);
        }
        if (\is_array($data) && \array_key_exists($piece, $data)) {
            return $data[$piece];
        }

        return [];
    }

    protected function cutMetadata($raw)
    {
        return  $this->dataPiece('metadata', $raw);
    }

    protected function factoryMetadata($raw)
    {
        $data = $this->cutMetadata($raw);

        if (!empty($data)) {
            $data = $this->normalizeMetas($data);
        }

        $this->Metadata = new Metadata($data);

        return true;
    }
}
