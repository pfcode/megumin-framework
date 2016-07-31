<?php

namespace pfcode\MeguminFramework;

/**
 * Class ModelFactory
 * @package pfcode\MeguminFramework
 * @author pfcode
 */
abstract class ModelFactory
{
    protected $productClass = null;

    protected $totalSelects = 0;
    protected $totalInserts = 0;
    protected $totalUpdates = 0;
    protected $totalDeletes = 0;

    protected abstract function getSingleModel($query, $forceForceAssoc = false);
    protected abstract function getMultipleModels($query, $forceForceAssoc = false);
    protected abstract function getModelsCount($query);

    protected abstract function insertModel($query, $returnEntityID = false);
    protected abstract function updateModel($query);
    protected abstract function deleteModel($query);

    /**
     * @return string
     */
    protected function getProductClass(){
        return $this->productClass;
    }

    /**
     * Specify class of products to instantiate
     * If not specified, results will be presented as associative arrays
     * @param $class
     */
    protected function setProductClass($class){
        $this->productClass = $class;
    }

    /**
     * Return sum of performed queries
     * @return int
     */
    public function getTotalQueries(){
        return $this->totalSelects
            + $this->totalInserts
            + $this->totalUpdates
            + $this->totalDeletes;
    }

    /**
     * @return int
     */
    public function getTotalSelects()
    {
        return $this->totalSelects;
    }

    /**
     * @return int
     */
    public function getTotalInserts()
    {
        return $this->totalInserts;
    }

    /**
     * @return int
     */
    public function getTotalUpdates()
    {
        return $this->totalUpdates;
    }

    /**
     * @return int
     */
    public function getTotalDeletes()
    {
        return $this->totalDeletes;
    }
}