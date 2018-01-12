<?php

namespace Mpdf\Utils;

class Arrays
{

    public static function get($array, $key, $default = null)
    {
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }

        if (func_num_args() < 3) {
            throw new \InvalidArgumentException(sprintf('Array does not contain key "%s"', $key));
        }

        return $default;
    }

    /**
     * Find value in array and return it
     * @param array $array   Array with values
     * @param mixed $key     Key
     * @param mixed $default Value default returned if not found key
     * @return mixed
     */
    public static function findValue($array, $key, $default = null)
    {
        return empty($array[$key]) === true ? $default : $array[$key];
    }

}
