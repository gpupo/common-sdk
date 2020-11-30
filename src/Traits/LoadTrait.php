<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Traits;

trait LoadTrait
{
    protected function loadArrayFromFile($filename, array $env = [], $merge = true)
    {
        if (true !== file_exists($filename)) {
            return ['not_found' => $filename];
        }
        foreach ($env as $k => $v) {
            ${$k} = $v;
        }

        $array = include $filename;
        if (!\is_array($array)) {
            return [];
        }

        if (true !== $merge) {
            return $array;
        }

        return array_merge($env, $array);
    }
}
