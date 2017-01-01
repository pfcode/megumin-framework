<?php

namespace pfcode\MeguminFramework;

use \Smarty;

class SmartyView extends View
{
    private static $templateDir;
    private static $primaryTemplate;
    private static $plugins = array();

    /**
     * @var Smarty
     */
    private $smarty;

    public $isStandalone = false;
    public $templateFile;

    /**
     * @param $absolute_path
     */
    public static function setTemplateDir($absolute_path){
        self::$templateDir = $absolute_path;
    }

    /**
     * @return Smarty
     */
    public function createSmartyInstance(){
        $smarty = new Smarty();

        $smarty->force_compile = true;
        $smarty->debugging = false;
        $smarty->caching = true;
        $smarty->cache_lifetime = 120;

        return $smarty;
    }

    /**
     * @param bool $outputHTML
     * @return bool|string
     */
    public function display($outputHTML = false)
    {
        $this->smarty = $this->createSmartyInstance();

        $this->smarty->setTemplateDir(self::$templateDir);

        $this->registerPlugin("function", "date", array($this, "plugin_date"));
        $this->registerAllPlugins();

        foreach($this->varScope as $key => $value){
            $this->smarty->assign($key, $value);
        }

        if($this->isStandalone){
            $targetTemplate = $this->templateFile;
        } else{
            $targetTemplate = self::$primaryTemplate;
            $this->smarty->assign("template_file", $this->templateFile);
        }

        $this->smarty->assign("page_title", $this->getPageTitle());

        try{
            if($outputHTML){
                return $this->smarty->fetch($targetTemplate);
            } else{
                $this->smarty->display($targetTemplate);
            }
        } catch(\SmartyException $e){
            die("SmartyException: " . $e->getMessage());
        }

        return true;
    }

    /**
     * @param mixed $primaryTemplate
     */
    public static function setPrimaryTemplate($primaryTemplate)
    {
        self::$primaryTemplate = $primaryTemplate;
    }

    /**
     * @param $params
     * @param $smarty
     */
    public function plugin_date($params, $smarty){
        if(isset($params['format'])){
            $format = $params['format'];
        } else{
            $format = "d-m-Y";
        }
        echo date($format, $params['t']);
    }

    /**
     * Add new plugin to the queue (will be registered after Smarty object instantiation)
     * @param $type
     * @param $name
     * @param $callback
     */
    public static function registerPlugin($type, $name, $callback){
        array_push(self::$plugins, array(
            "type" => $type,
            "name" => $name,
            "callback" => $callback
        ));
    }

    /**
     * Register all queued plugins in current Smarty instance
     */
    public function registerAllPlugins(){
        foreach(self::$plugins as $plugin){
            $this->smarty->registerPlugin($plugin['type'], $plugin['name'], $plugin['callback']);
        }
    }
}