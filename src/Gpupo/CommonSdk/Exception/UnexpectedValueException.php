<?php

namespace Gpupo\CommonSdk\Exception;

class UnexpectedValueException extends \UnexpectedValueException implements ExceptionInterface
{
    public function toLog()
    {
        return [
            'message'   => $this->message,
            'code'      => $this->code,
        ];
    }
}
