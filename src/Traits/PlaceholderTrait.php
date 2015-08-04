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

trait PlaceholderTrait
{
    protected function fillPlaceholdersWithArray($string, array $array)
    {
        foreach ($array as $key => $value) {
            $string = str_replace([
                '{'.$key.'}',
                '{'.strtoupper($key).'}',
            ], $value, $string);
        }

        return $string;
    }
}
