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

use Exception;
use Gpupo\Common\Console\AbstractApplication as Core;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractApplication extends Core
{
    protected function getLogFilePath()
    {
        return 'var/logs/main.log';
    }

    protected function getLogLevel()
    {
        return Logger::DEBUG;
    }

    public function factoryLogger($channel = 'bin', $verbose = null)
    {
        $logger = new Logger($channel);
        $logger->pushHandler(new StreamHandler($this->getLogFilePath(), $this->getLogLevel()));

        if (!empty($verbose)) {
            $logger->pushHandler(new ErrorLogHandler(0, Logger::INFO));
        }

        return $logger;
    }

    public function appendCommand($name, $description, array $definition = [])
    {
        return $this->register($name)
            ->setDescription($description)
            ->setDefinition($this->factoryDefinition($definition));
    }

    public function showException(Exception $e, OutputInterface $output, $description = 'Erro')
    {
        $output->writeln('<error>'.$description.'</error>');
        $output->writeln('Message: <comment>'.$e->getMessage().'</comment>');
        $output->writeln('Error Code: <comment>'.$e->getCode().'</comment>');
    }
}
