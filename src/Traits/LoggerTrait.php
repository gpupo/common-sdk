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

namespace Gpupo\CommonSdk\Traits;

use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    use \Psr\Log\LoggerTrait;
    use \Psr\Log\LoggerAwareTrait;

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

    public function initLogger($logger)
    {
        if (!empty($logger)) {
            return $this->setLogger($logger);
        }
    }

    public function log($level, $message, array $context = [])
    {
        if ($this->getLogger()) {
            return $this->getLogger()->log($level, $message, $context);
        }
    }
}
