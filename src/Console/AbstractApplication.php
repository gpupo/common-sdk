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

namespace Gpupo\CommonSdk\Console;

use Gpupo\Common\Console\AbstractApplication as Core;
use Gpupo\CommonSchema\TranslatorDataCollection;
use Gpupo\CommonSdk\FactoryInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Gpupo\CommonSdk\Factory;

abstract class AbstractApplication extends Core
{
    public function factorySdk(array $options, LoggerInterface $logger = null, CacheInterface $cache = null): FactoryInterface
    {
        return new Factory($options, $logger, $cache);
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

    public function init(string $namespace, string $rootDirectory)
    {
        $input = new ArgvInput();
        $output = new ConsoleOutput();
        $config = getenv();

        if (!empty($config['extra_file'])) {
            $localFilename = sprintf('%s/%s', $rootDirectory, $config['extra_file']);
            if (file_exists($localFilename)) {
                $data = Yaml::parseFile($localFilename);
                if (\is_array($data)) {
                    $config = array_merge($config, $data);
                }
            }
        }

        $logger = new Logger('console');
        $logger->pushHandler(new StreamHandler($this->getLogFilePath(), $this->getLogLevel()));
        if ('true' === $config['APP_DEBUG']) {
            $logger->pushHandler(new ErrorLogHandler(0, $this->getLogLevel()));
        }

        $factory = $this->factorySdk($config, $logger, new FilesystemCache());

        $finder = new Finder();
        $finder->files()->name('*Command.php')->notName('*Abstract*')->in(sprintf('%s/src/Console/Command', $rootDirectory));

        foreach ($finder as $file) {
            $class = str_replace('.php', '', $file->getRelativePathname());
            $segments = explode('/', $class);
            $lastPart = implode('\\', $segments);
            $class = $namespace.'\\'.$lastPart;
            if (!class_exists($class)) {
                $class = $namespace.'\\Command\\'.$lastPart;
            }
            $this->add(new $class($factory));
        }

        $this->displayInstructionsBanner($output);
    }

    protected function displayInstructionsBanner(OutputInterface $output)
    {
        $output->writeln([
            '',
            sprintf(':: <bg=green;options=bold> %s </>', $this->getName()),
            '',
            '<options=bold>Atenção!</> Esta aplicação é apenas uma ferramenta de apoio ao desenvolvedor e não deve ser usada no ambiente de produção!',
            '',
        ]);
    }
}
