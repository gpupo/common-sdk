<?php

/*
 * This file is part of common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\CommonSdk\Entity;

use Gpupo\CommonSdk\Exception\InvalidArgumentException;

class EntityTools
{
    public static function getInitValue($data, $key, $default = '')
    {
        if (is_array($data) && array_key_exists($key, $data)) {
            $fill = $data[$key];
            if (is_array($default) && !is_array($fill)) {
                $fill = [$key => $fill];
            }
        }

        return (isset($fill)) ? $fill : $default;
    }

    public static function normalizeType($data, $type)
    {
        switch ($type) {
            case 'integer':
                return intval($data);
            case 'boolean':
                return boolval($data);
            case 'number':
            case 'float':
                return floatval($data);
            default:
                return $data;
        }
    }

    public static function validate($key, $current, $value, $required = false)
    {
        $empty = function ($value) use ($required) {
            return ($required) ? false : empty($value);
        };

        $throw = function () use ($key, $current, $value) {
            throw new InvalidArgumentException($key
                .' should have value of type '.$value
                .' valid.['.$current.'] received.');
        };

        if ($empty($current)) {
            return true;
        }

        if ($value === 'integer' && intval($current) !== $current) {
            $throw();
        }

        if ($value === 'number' && !is_numeric($current)) {
            $throw();
        }

        if ($value === 'string' && strlen($current) < 1) {
            $throw();
        }

        return true;
    }
}
