<?php

namespace Gpupo\CommonSdk\Traits;

Trait ExceptionTrait
{
    public function toLog()
    {
        return [
            'message'   => $this->message,
            'code'      => $this->code,
        ];
    }
}
