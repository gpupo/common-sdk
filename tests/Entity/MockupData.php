<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Tests\Entity;

/**
 * @covers \Gpupo\CommonSdk\Entity\EntityAbstract
 */
class MockupData
{
    public static function create($className)
    {
        $object = new $className();

        $data = [];

        foreach ($object->getSchema() as $k => $v) {
            if ('object' === $v || 'array' === $v) {
                $x = $data;
            } elseif ('number' === $v) {
                $x = (float) rand(99, 99999) / rand(2, 222);
            } elseif ('integer' === $v) {
                $x = (int) (rand(99, 99999));
            } else {
                $x = sha1($k.uniqid());
            }

            $data[$k] = $x;
        }

        return $data;
    }
}
