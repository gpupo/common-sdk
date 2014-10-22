<?php
namespace Gpupo\CommonSdk\Traits;

trait LoggerTrait
{
    use \Psr\Log\LoggerTrait;
    use \Psr\Log\LoggerAwareTrait;

    public function getLogger()
    {
        return $this->logger;
    }
    
    public function initLogger($logger)
    {
        if (!empty($logger)) {
            return $this->setLogger($logger);
        }
    }
    public function log($level, $message, array $context = array())
    {
        if ($this->getLogger()) {
            return $this->getLogger()->log($level, $message, $context);
        }
    }
}
