<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Traits;

trait PlaceholderTrait
{
    protected function fillPlaceholdersWithArray(string $string, array $array): string
    {
        foreach ($array as $key => $value) {
            $string = str_replace([
                '{'.$key.'}',
                '{'.mb_strtoupper($key).'}',
            ], (string) $value, $string);
        }

        return $string;
    }
}
