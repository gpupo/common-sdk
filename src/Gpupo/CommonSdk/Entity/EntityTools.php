<?php

namespace Gpupo\CommonSdk\Entity;

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

    public static function validate($current, $value)
    {
        if ($value == 'integer') {
            if (!empty($current) && intval($current) !== $current) {
                throw new \InvalidArgumentException($key
                    . ' should have value of type Integer valid (received '
                    . $current . ')');
            }
        } elseif (!empty($current) && $value == 'number') {
            if (!is_numeric($current)) {
                throw new \InvalidArgumentException($key
                    . ' should have value of type Number valid');
            }
        }

        return true;
    }

}
