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

namespace Gpupo\CommonSdk\Console;

use Gpupo\Common\Console\AbstractApplication as Core;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class AbstractApplication extends Core
{
    protected function getLogFilePath()
    {
        return 'Resources/logs/main.log';
    }

    protected function getLogLevel()
    {
        return Logger::DEBUG;
    }

    public function factoryLogger()
    {
        $logger = new Logger('bin');
        $logger->pushHandler(new StreamHandler($this->getLogFilePath(), $this->getLogLevel()));

        return $logger;
    }
}
