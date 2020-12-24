<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Traits;

use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    use \Psr\Log\LoggerAwareTrait;
    use \Psr\Log\LoggerTrait;

    public function getLogger()
    {
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger = null)
    {
        if ($logger) {
            $this->logger = $logger;
        }

        return $this;
    }

    public function initLogger($logger, $channel = null): void
    {
        if (empty($logger)) {
            return;
        }

        if (empty($channel)) {
            $this->setLogger($logger);
        } else {
            $this->setLogger($logger->withName($channel));
        }
    }

    public function log($level, $message, array $context = [])
    {
        if ($this->getLogger()) {
            return $this->getLogger()->log($level, $message, $context);
        }
    }
}
