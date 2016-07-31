<?php

namespace pfcode\MeguminFramework;

/**
 * Class View
 * @package pfcode\MeguminFramework
 * @author pfcode
 */
abstract class View
{
    private static $globalVarScope = array();
    protected static $pageTitleSuffix = null;
    protected static $pageTitlePrefix = null;
    protected static $pageTitleSeparator = "|";

    protected $pageTitle = null;

    protected $varScope = array();

    /**
     * View constructor.
     */
    public function __construct()
    {
        // Set global var scope as current
        $this->varScope = self::$globalVarScope;
    }

    /**
     * Build page title (include prefix or/and suffix if set)
     * @return string
     */
    protected function getPageTitle(){
        return (strlen(self::$pageTitlePrefix) ? self::$pageTitlePrefix . self::$pageTitleSeparator : "")
            . $this->pageTitle
            . (strlen(self::$pageTitleSuffix) ? self::$pageTitleSeparator . self::$pageTitleSuffix : "");
    }

    /**
     * @param $title
     */
    public function setPageTitle($title){
        $this->pageTitle = $title;
    }

    /**
     * @param $prefix
     */
    public static function setPageTitlePrefix($prefix){
        self::$pageTitlePrefix = $prefix;
    }

    /**
     * @param $separator
     */
    public static function setPageTitleSeparator($separator){
        self::$pageTitleSeparator = $separator;
    }

    /**
     * @param $suffix
     */
    public static function setPageTitleSuffix($suffix){
        self::$pageTitleSuffix = $suffix;
    }

    /**
     * Assign value to new variable in current scope
     * @param $key
     * @param $value
     */
    public function setScopeKey($key, $value){
        $this->varScope[$key] = $value;
    }

    /**
     * Clear and set new variables scope
     * @param $newScope
     * @param bool $ignoreGlobalScope
     */
    public function setScope($newScope, $ignoreGlobalScope = false){
        if(!is_array($newScope)){
            $this->varScope = array();
        } else{
            $this->varScope = $newScope;
        }

        if(!$ignoreGlobalScope){
            $this->varScope = array_merge($this->varScope, self::$globalVarScope);
        }
    }

    /**
     * @return array
     */
    protected function getGlobalScope(){
        return self::$globalVarScope;
    }

    /**
     * Clear and set new global variables scope
     * @param $newGlobalScope
     */
    public static function setGlobalScope($newGlobalScope){
        if(!is_array($newGlobalScope)){
            self::$globalVarScope = array();
        } else{
            self::$globalVarScope = $newGlobalScope;
        }
    }

    /**
     * Display current view
     * @param bool $outputHTML
     * @return mixed
     */
    public abstract function display($outputHTML = false);
}