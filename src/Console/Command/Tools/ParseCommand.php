<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Console\Command\Tools;

use Gpupo\Common\Traits\TableTrait;
use Gpupo\CommonSdk\Console\Command\AbstractCommand;
use Gpupo\CommonSdk\Traits\ResourcesTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ParseCommand extends AbstractCommand
{
    use ResourcesTrait;
    use TableTrait;

    protected function configure()
    {
        $this
            ->setName('tools:parse')
            ->setDescription('Parse a Json File')
            ->addArgument('filename', InputArgument::REQUIRED, 'A json path');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');

        $type = end(explode('.', $filename));

        switch ($type) {
            case 'yaml':
            case 'yml':
                $data = $this->resourceDecodeYamlFile($filename);

                break;
            default:
                $data = $this->resourceDecodeJsonFile($filename);

                break;
        }

        $this->displayTableResults($output, $data);

        return 0;
    }
}
