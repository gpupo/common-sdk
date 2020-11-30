<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Traits;

trait DocumentationTrait
{
    /**
     * @internal
     *
     * @param mixed $name
     * @param mixed $returnType
     */
    private function __resolvReturn($name, $returnType)
    {
        if ('number' === $returnType) {
            return 'float';
        }

        if (Tools::isObjectType($returnType)) {
            $method = 'get'.ucfirst($name);
            $className = \get_class($this->{$method}());

            return $className;
        }

        return $returnType;
    }

    abstract public function getSchema(): array;

    /**
     * @internal
     */
    public function toDocBlock()
    {
        $data = [
            'description' => property_exists($this, 'description') ? $this->description : false,
            'class' => static::class,
            'entity' => true,
            'schema' => [],
        ];

        foreach ($this->getSchema() as $name => $type) {
            $data['schema'][] = [
                'name' => $name,
                'type' => $type,
                'return' => $this->__resolvReturn($name, $type),
            ];
        }

        return $data;
    }
}
