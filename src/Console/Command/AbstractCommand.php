<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Console\Command;

use Gpupo\Common\Console\Command\AbstractCommand as Core;
use Gpupo\CommonSdk\FactoryInterface;

abstract class AbstractCommand extends Core
{
    protected $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;

        parent::__construct();
    }

    public function getFactory(): FactoryInterface
    {
        if (!$this->factory instanceof FactoryInterface) {
            throw new \InvalidArgumentException('Factory must be defined!');
        }

        return $this->factory;
    }

    public function getProjectDataFilename(): string
    {
        return $this->getFactory()->getOptions()->get('extra_file') ?: 'var/parameters.yaml';
    }
}
