<?php

namespace pfcode\MeguminFramework;
use \mysqli;

/**
 * Class MySQLModelFactory
 * @package pfcode\MeguminFramework
 * @author pfcode
 */
abstract class MySQLModelFactory extends ModelFactory
{
    /**
     * @var \mysqli
     */
    protected static $database;

    public $timeSelect = 0;
    public $timeSelectFetch = 0;
    public $timeInsert = 0;
    public $timeInsertFetch = 0;
    public $timeDelete = 0;
    public $timeUpdate = 0;

    /**
     * @param mysqli $mysqli
     */
    public static function setDatabase(mysqli $mysqli){
        self::$database = $mysqli;
    }

    /**
     * Execute SELECT query and return array of object
     * @param $query
     * @param $forceAssoc
     * @return Model[]|[][]|bool
     */
    protected function getMultipleModels($query, $forceAssoc = false){
        $this->totalSelects++;

        if(empty($this->getProductClass())){
            $forceAssoc = true;
        }

        $timeStart = microtime(1);
        $result = self::$database->query($query);
        $this->timeSelect += (microtime(1) - $timeStart);

        $timeStart = microtime(1);
        if(!$result) {
            $ret = false;
        } else{
            $ret = array();

            if($forceAssoc){
                while($x = $result->fetch_assoc()){
                    array_push($ret, $x);
                }
            } else{
                while($x = $result->fetch_object($this->getProductClass())){
                    array_push($ret, $x);
                }
            }
        }
        $this->timeSelectFetch += (microtime(1) - $timeStart);

        return $ret;
    }

    /**
     * Execute SELECT query and return single object
     * @param $query
     * @param $forceAssoc
     * @return Model|[]|bool
     */
    protected function getSingleModel($query, $forceAssoc = false){
        $this->totalSelects++;

        if(empty($this->getProductClass())){
            $forceAssoc = true;
        }

        $timeStart = microtime(1);
        $result = self::$database->query($query);
        $this->timeSelect += (microtime(1) - $timeStart);

        $timeStart = microtime(1);
        if(!$result) {
            $ret = false;
        } else{
            if($forceAssoc){
                $ret = $result->fetch_assoc();
            } else{
                $ret =  $result->fetch_object($this->getProductClass());
            }
        }
        $this->timeSelectFetch += (microtime(1) - $timeStart);

        return $ret;
    }

    /**
     * Execute SELECT COUNT(*)-like query
     * @param $query
     * @return int|bool
     */
    protected function getModelsCount($query){
        $this->totalSelects++;

        $timeStart = microtime(1);
        $result = self::$database->query($query);
        $this->timeSelect += (microtime(1) - $timeStart);

        $timeStart = microtime(1);
        if(!$result) {
            $ret = false;
        } else{
            $ret = $result->fetch_array();
            if(is_array($ret)){
                $ret = $ret[0];
            } else{
                $ret = false;
            }
        }
        $this->timeSelectFetch += (microtime(1) - $timeStart);

        return $ret;
    }

    /**
     * Execute INSERT query
     * @param $query
     * @param bool|false $returnEntityID
     * @return bool|int
     */
    protected function insertModel($query, $returnEntityID = false){
        $this->totalInserts++;

        $timeStart = microtime(1);
        $result = self::$database->query($query);
        $this->timeInsert += (microtime(1) - $timeStart);

        $timeStart = microtime(1);
        if(!$result){
            $ret = false;
        } else if($returnEntityID){
            $result = self::$database->query("SELECT LAST_INSERT_ID()");
            if(!$result){
                $ret = false;
            } else{
                $ret = $result->fetch_array();
                if(is_array($ret)){
                    $ret = $ret[0];
                } else{
                    $ret = false;
                }
            }
        } else{
            $ret = true;
        }
        $this->timeInsertFetch += (microtime(1) - $timeStart);

        return $ret;
    }

    /**
     * Execute UPDATE query
     * @param $query
     * @return bool
     */
    protected function updateModel($query){
        $this->totalInserts++;

        $timeStart = microtime(1);
        $result = self::$database->query($query);
        $this->timeUpdate += (microtime(1) - $timeStart);

        if(!$result) {
            return false;
        } else{
            return true;
        }
    }

    /**
     * Execute DELETE query
     * @param $query
     * @return bool
     */
    protected function deleteModel($query){
        $this->totalDeletes++;

        $timeStart = microtime(1);
        $result = self::$database->query($query);
        $this->timeDelete += (microtime(1) - $timeStart);

        if(!$result) {
            return false;
        } else{
            return true;
        }
    }
    /**
     * @param $string
     * @return string
     */
    protected function escapeString($string){
        return self::$database->escape_string($string);
    }

    /**
     * @param $numeric
     * @return int
     */
    protected function escapeNumeric($numeric){
        return doubleval(str_replace(',', '.', $numeric));
    }

}