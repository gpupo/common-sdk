<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\CommonSdk\Traits;

trait DocumentationTrait
{
    abstract public function getSchema();

    public function documentationClassDocblock()
    {
        $list = ['Magic methods on '.get_called_class().":\n *"];

        foreach ($this->getSchema() as $key => $value) {
            $name = ucfirst($key);
            $return  = $this->documentationResolvReturn($name, $value);
            $list[] = '* @method '.$return.' get'.$name.'()';
            $list[] = '* @method set'.$name.'('.$return.' $'.$key.')';
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
