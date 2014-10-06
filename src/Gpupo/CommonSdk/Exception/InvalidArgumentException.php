<?php

namespace Gpupo\CommonSdk\Exception;

use Gpupo\CommonSdk\Traits\ExceptionTrait;

class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    use ExceptionTrait;
}
