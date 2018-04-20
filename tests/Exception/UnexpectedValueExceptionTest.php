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

use Gpupo\CommonSdk\Exception\UnexpectedValueException;
use Gpupo\Tests\CommonSdk\TestCaseAbstract;

/**
 * @coversDefaultClass \Gpupo\CommonSdk\Exception\UnexpectedValueException
 */
class UnexpectedValueExceptionTest extends TestCaseAbstract
{
    /**
     * @return \Gpupo\CommonSdk\Exception\UnexpectedValueException
     */
    public function dataProviderUnexpectedValueException()
    {
        return [[new UnexpectedValueException()]];
    }

    /**
     * @testdox ``setMessage()``
     * @cover ::setMessage
     * @dataProvider dataProviderUnexpectedValueException
     */
    public function testSetMessage(UnexpectedValueException $unexpectedValueException)
    {
        $this->markIncomplete('setMessage() need implementation!');
    }

    /**
     * @testdox ``toLog()``
     * @cover ::toLog
     * @dataProvider dataProviderUnexpectedValueException
     */
    public function testToLog(UnexpectedValueException $unexpectedValueException)
    {
        $this->markIncomplete('toLog() need implementation!');
    }

    /**
     * @testdox ``addMessagePrefix()``
     * @cover ::addMessagePrefix
     * @dataProvider dataProviderUnexpectedValueException
     */
    public function testAddMessagePrefix(UnexpectedValueException $unexpectedValueException)
    {
        $this->markIncomplete('addMessagePrefix() need implementation!');
    }
}
