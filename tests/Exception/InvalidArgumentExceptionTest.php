<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\Tests\CommonSdk\Exception;

use Gpupo\CommonSdk\Exception\InvalidArgumentException;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

/**
 * @coversDefaultClass \Gpupo\CommonSdk\Exception\InvalidArgumentException
 */
class InvalidArgumentExceptionTest extends TestCaseAbstract
{
    /**
     * @return \Gpupo\CommonSdk\Exception\InvalidArgumentException
     */
    public function dataProviderInvalidArgumentException()
    {
        return [[new InvalidArgumentException()]];
    }

    /**
     * @testdox ``setMessage()``
     * @cover ::setMessage
     * @dataProvider dataProviderInvalidArgumentException
     */
    public function testSetMessage(InvalidArgumentException $invalidArgumentException)
    {
        $this->markIncomplete('setMessage() need implementation!');
    }

    /**
     * @testdox ``toLog()``
     * @cover ::toLog
     * @dataProvider dataProviderInvalidArgumentException
     */
    public function testToLog(InvalidArgumentException $invalidArgumentException)
    {
        $this->markIncomplete('toLog() need implementation!');
    }

    /**
     * @testdox ``addMessagePrefix()``
     * @cover ::addMessagePrefix
     * @dataProvider dataProviderInvalidArgumentException
     */
    public function testAddMessagePrefix(InvalidArgumentException $invalidArgumentException)
    {
        $this->markIncomplete('addMessagePrefix() need implementation!');
    }
}
