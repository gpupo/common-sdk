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

        $returnType = [
            'number'    => 'mixed',
            'object'    => '\Gpupo\CommonSdk\Entity\EntityInterface',
        ];

        foreach ($this->getSchema() as $key => $value) {
            $name = ucfirst($key);
            $return  = array_key_exists($value, $returnType) ? $returnType[$value] : $value;
            $list[] = '* @method '.$return.' get'.$name.'();';
            $list[] = '* @method set'.$name.'('.$return.');';
        }

        return $list;
    }
}
