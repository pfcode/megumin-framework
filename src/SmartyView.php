<?php

namespace pfcode\MeguminFramework;

use \Smarty;

class SmartyView extends View
{
    private static $templateDir;
    private static $primaryTemplate;

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
     * @param bool $outputHTML
     * @return bool|string
     */
    public function display($outputHTML = false)
    {
        $this->smarty = new Smarty();

        $this->smarty->force_compile = true;
        $this->smarty->debugging = false;
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 120;

        $this->smarty->setTemplateDir(self::$templateDir);

        $this->registerPlugins();

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

    public function registerPlugins(){
        $this->smarty->registerPlugin("function", "date", array($this, "plugin_date"));
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
}