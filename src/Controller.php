<?php

namespace pfcode\MeguminFramework;

/**
 * Class Controller
 * @package pfcode\MeguminFramework
 * @author pfcode
 */
abstract class Controller
{
    protected static $ajaxActionKey = "action";

    /**
     * Controller arguments
     * @var array
     */
    protected $args;

    /**
     * Current view object
     * @var View
     */
    protected $view;

    /**
     * Returned value of ajax call (if performed)
     * @var mixed
     */
    protected $ajaxReturned = null;

    /**
     * Controller constructor.
     * @param array $args
     */
    public final function __construct(array $args){
        $this->args = $args;


        if(isset($_POST[self::$ajaxActionKey])) {
            $this->performAjaxRouting();
        }

        $this->execute();
    }

    /**
     * Output specified data as JSON and die
     * @param $data
     */
    protected function outputJSON($data){
        ob_clean();

        header('Content-Type: application/json');
        echo json_encode($data);

        die();
    }

    /**
     * Output error message in JSON format
     * @param $message
     * @param $params
     */
    protected function errorJSON($message, $params){
        $this->outputJSON(array(
            "status" => "error",
            "message" => $message,
            "params" => $params,
            "time" => time()
        ));
    }

    /**
     * Default, empty dispatcher
     * @return bool
     */
    protected function ajaxDispatcher(){
        return false;
    }

    /**
     * Dispatch ajax action
     */
    protected function performAjaxRouting(){
        $mappings = $this->ajaxDispatcher();

        if(is_array($mappings) && isset($mappings[$_POST[self::$ajaxActionKey]])){
            $this->ajaxReturned = call_user_func($mappings[$_POST[self::$ajaxActionKey]]);
        }
    }

    /**
     * Place for the controller logic
     * @return mixed
     */
    abstract protected function execute();
}