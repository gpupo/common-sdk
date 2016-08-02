<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\Tests\CommonSdk\Entity;

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
                $x = intval(rand(99, 99999));
            } else {
                $x = sha1($k.uniqid());
            }

            $data[$k] = $x;
        }

        return $data;
    }
}
