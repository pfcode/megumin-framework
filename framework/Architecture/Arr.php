<?php
/**
 * Created by PhpStorm.
 * User: pfcode
 * Date: 25.02.17
 * Time: 15:19
 */

namespace pfcode\MeguminFramework\Architecture;


class Arr
{
    /**
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed
     */
    public static function get($array, $key, $default = null) : mixed{
        if(is_array($array) && isset($array[$key])){
            return $array[$key];
        }

        return $default;
    }
}