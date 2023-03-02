<?php

namespace pfcode\MeguminFramework;

/**
 * Class Hook
 * @package pfcode\MeguminFramework
 * @author pfcode
 */
class Hook
{
    private static $hooks = array();

    /**
     * Add function callback to specified hook queue
     * @param $hookName
     * @param $callable
     * @param bool $prepend
     */
    public static function registerHook($hookName, $callable, $prepend = false){
        if(!isset(self::$hooks[$hookName])){
            self::$hooks[$hookName] = array();
        }

        if($prepend){
            array_unshift(self::$hooks[$hookName], $callable);
        } else{
            array_push(self::$hooks[$hookName], $callable);
        }
    }

    /**
     * Execute hooks queue, if hook is registered
     * @param $hookName
     * @param $params
     */
    public static function executeHook($hookName, $params){
        if(isset(self::$hooks[$hookName])){
            foreach(self::$hooks[$hookName] as $funcCallback){
                if(is_callable($funcCallback)){
                    $ret = call_user_func($funcCallback, ...$params);

                    if($ret === false){
                        break;
                    }
                }
            }
        }
    }

    /**
     * Deregister all functions from specified hook
     * @param $hookName
     */
    public static function deregisterHooks($hookName){
        if(isset(self::$hooks[$hookName])){
            unset(self::$hooks[$hookName]);
        }
    }
}