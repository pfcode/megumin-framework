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
     * Controller constructor.
     * @param array $args
     */
    public final function __construct(array $args){
        $this->args = $args;


        if(isset($_POST[self::$ajaxActionKey])) {
            $this->ajaxDispatcher();
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
     * Dispatch ajax action
     */
    protected function ajaxDispatcher(){
//        switch($_POST[self::$ajaxActionKey]){
//            default:
//                break;
//        }

        // Nothing's here
    }

    /**
     * Place for the controller logic
     * @return mixed
     */
    abstract protected function execute();
}