<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://www.gpupo.com/>.
 */

namespace Gpupo\CommonSdk\Console;

use Exception;
use Gpupo\Common\Console\AbstractApplication as Core;
use Gpupo\CommonSchema\TranslatorDataCollection;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractApplication extends Core
{
    protected $configAlias = [
        'env' => 'version',
    ];

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

    public function displayOrderList(TranslatorDataCollection $collection, OutputInterface $output)
    {
        if (0 === $collection->count()) {
            return $output->writeln('<info>Nenhum pedido para exibir</info>');
        }

        return $this->displayTableResults($output, $collection->toArray(), [
            'merchant', 'orderNumber', 'acceptedOffer', 'orderDate',
            'customer', 'billingAddress', 'quantity', 'freight', 'total',
        ], 49, true);
    }

    public function jsonLoadFromFile($filename)
    {
        if (!file_exists($filename)) {
            throw new Exception('Filename '.$filename.' not exists!');
        }

        $string = file_get_contents($filename);

        return json_decode($string, true);
    }

    public function jsonSaveToFile(array $array, $filename, OutputInterface $output)
    {
        $json = json_encode($array, JSON_PRETTY_PRINT);
        file_put_contents($filename, $json);

        return $output->writeln('Arquivo <info>'.$filename.'</info> gerado.');
    }
}
