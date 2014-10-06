<?php

namespace Gpupo\CommonSdk\Exception;

use Gpupo\CommonSdk\Traits\ExceptionTrait;

class UnexpectedValueException extends \UnexpectedValueException implements ExceptionInterface
{
    use ExceptionTrait;
}
