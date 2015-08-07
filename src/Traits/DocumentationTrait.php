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
            'description'   => property_exists($this, 'description') ? $this->description : false,
            'class'         => get_called_class(),
            'schema'        => [],
        ];

        foreach ($this->getSchema() as $name => $type) {
            $data['schema'][] = [
                'name'      => $name,
                'type'      => $type,
                'return'    => $this->__resolvReturn($name, $type),
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
