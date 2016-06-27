<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
