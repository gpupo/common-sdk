<?php

namespace Gpupo\CommonSdk\Exception;

class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    public function toLog()
    {
        return [
            'message'   => $this->message,
            'code'      => $this->code,
        ];        
    }
}
