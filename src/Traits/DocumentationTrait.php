<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://www.gpupo.com/>.
 */

namespace Gpupo\CommonSdk\Traits;

trait DocumentationTrait
{
    abstract public function getSchema();

    /**
     * @internal
     */
    public function toDocBlock()
    {
        $data = [
            'description' => property_exists($this, 'description') ? $this->description : false,
            'class'       => get_called_class(),
            'entity'      => true,
            'schema'      => [],
        ];

        foreach ($this->getSchema() as $name => $type) {
            $data['schema'][] = [
                'name'   => $name,
                'type'   => $type,
                'return' => $this->__resolvReturn($name, $type),
            ];
        }

        return $data;
    }

    /**
     * @internal
     */
    private function __resolvReturn($name, $returnType)
    {
        if ($returnType === 'number') {
            return 'float';
        }

        if ($returnType === 'object') {
            $method = 'get'.ucfirst($name);
            $className = get_class($this->$method());

            return $className;
        }

        return $returnType;
    }
}
