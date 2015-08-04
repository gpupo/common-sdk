<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For more information, see
 * <http://www.g1mr.com/common-sdk/>.
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

    public function toLog()
    {
        return [
            'message'   => $this->getMessage(),
            'code'      => $this->getCode(),
        ];
    }

    public function addMessagePrefix($string)
    {
        $this->setMessage($string.' '.$this->getMessage());
    }
}
