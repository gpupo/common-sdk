<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\CommonSdk\Traits;

/**
 * @property string $message
 */
trait ExceptionTrait
{
    abstract public function getMessage();

    abstract public function getCode();

    public function setMessage($string)
    {
        $this->message = $string;
    }

    public function toLog(): array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            // 'file' => $this->getFile(),
            // 'line' => $this->getLine(),
        ];
    }

    public function addMessagePrefix($string)
    {
        $this->setMessage($string.' '.$this->getMessage());
    }
}
