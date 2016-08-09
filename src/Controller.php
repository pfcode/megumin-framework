<?php

namespace pfcode\MeguminFramework;

/**
 * Class Controller
 * @package pfcode\MeguminFramework
 * @author pfcode
 */
abstract class Controller
{
    protected static $postActionKey = "action";

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
    protected $postReturned = null;

    /**
     * Controller constructor.
     * @param array $args
     */
    public final function __construct(array $args){
        $this->args = $args;

        if($this->hasPermission()){
            if(isset($_POST[self::$postActionKey])) {
                $this->performPostRouting();
            }

            $this->execute();
        } else{
            $this->errorJSON("You have no permission to access this controller");
        }
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
    protected function errorJSON($message, $params = null){
        $this->outputJSON(array(
            "status" => "error",
            "message" => $message,
            "params" => $params,
            "time" => time()
        ));
    }

    /**
     * Override to implement permission check to controller execution and POST actions
     * @return bool
     */
    protected function hasPermission(){
        return true;
    }

    /**
     * Default, empty POST dispatcher
     * @return bool
     */
    protected function postDispatcher(){
        return false;
    }

    /**
     * Dispatch POST action
     */
    protected function performPostRouting(){
        $mappings = $this->postDispatcher();

        if(is_array($mappings) && isset($mappings[$_POST[self::$postActionKey]])){
            $this->postReturned = call_user_func($mappings[$_POST[self::$postActionKey]]);
        }
    }

    /**
     * Place for the controller logic
     * @return mixed
     */
    abstract protected function execute();
}