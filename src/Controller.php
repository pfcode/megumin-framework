<?php

namespace pfcode\MeguminFramework;

/**
 * Class Controller
 * @package pfcode\MeguminFramework
 * @author pfcode
 */
abstract class Controller
{
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
     * Place for the controller logic
     * @return mixed
     */
    abstract protected function execute();
}