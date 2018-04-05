<?php
class Models
{
    protected $ModelCollections;
    protected $ModelNameLookupTable;

    public function __construct()
    {
        $this->ModelCollections = array();
        $this->ModelNameLookupTable = array();
    }

    /* @return ModelCollection */
    public function __get($modelName)
    {
        if(array_key_exists($modelName, $this->ModelCollections)){
            return $this->ModelCollections[$modelName];
        }else{
            trigger_error("Model $modelName does not exists", E_USER_ERROR);
        }
    }

    public function Setup($modelCaches)
    {
        foreach($modelCaches as $modelName => $modelCache){
            $modelCollection = new ModelCollection();
            $modelCollection->ModelName = $modelName;
            $modelCollection->ModelCache = $modelCache;
            $this->AddModel($modelName, $modelCollection);
            $this->AddLookupTable($modelName, $modelCache['MetaData']['TableName']);
        }
    }

    public function AddModel($modelName, $model){
        if(isset($this->ModelCollections[$modelName])){
            return false;
        }

        $this->ModelCollections[$modelName] = $model;
    }

    public function AddLookupTable($modelName, $tableName)
    {
        $this->ModelNameLookupTable[$tableName] = $modelName;
    }

    public function GetModelForName($modelName)
    {
        if(!array_key_exists($modelName, $this->ModelCollections)){
            if($modelName == ''){
                $modelName = '[Empty]';
            }
            trigger_error('Missing model for table name ' . $modelName, E_USER_WARNING);
            return null;
        }

        return $this->ModelCollections[$modelName];
    }

    public function GetModelNameForTable($tableName)
    {
        if(array_key_exists($tableName, $this->ModelNameLookupTable)){
            return $this->ModelNameLookupTable[$tableName];
        }

        foreach($this->ModelNameLookupTable as $key => $value){
            if($tableName == $value){
                return $value;
            }
        }

        if($tableName === null){
            $tableName = '[null]';
        }else if($tableName === ''){
            $tableName = '[Empty]';
        }

        trigger_error('Missing model name for table name ' . $tableName, E_USER_WARNING);

        return null;
    }

    public function GetAll()
    {
        return $this->ModelCollections;
    }
}