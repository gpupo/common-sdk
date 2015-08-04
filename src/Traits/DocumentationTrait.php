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

    public function documentationClassDocblock()
    {
        $list = [];
        if (property_exists($this, 'description')) {
            $description = $this->description;
        } else {
            $description = 'Magic methods on '.get_called_class();
        }

        $list[] = $description."\n *";

        foreach ($this->getSchema() as $key => $value) {
            $name = ucfirst($key);
            $return  = $this->documentationResolvReturn($name, $value);
            $list[] = '* @method set'.$name.'('.$return.' $'.$key.') Define '.ucfirst($name);
            $list[] = '* @method '.$return.' get'.$name.'() Acesso a '.ucfirst($name);
        }

        return $list;
    }

    protected function documentationResolvReturn($name, $returnType)
    {
        if ($returnType === 'number') {
            return 'float';
        }

        if ($returnType === 'object') {
            $method = 'get'.$name;
            $className = get_class($this->$method());

            return $className;
        }

        return $returnType;
    }
}
