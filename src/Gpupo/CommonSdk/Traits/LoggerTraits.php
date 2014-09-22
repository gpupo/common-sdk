<?php
namespace Gpupo\CommonSdk\Traits;

trait LoggerTraits
{
    protected function addDebug($message, array $context = null)
    {
        echo "Message . $message \n\n";
        print_r($context);
        
    }
}
