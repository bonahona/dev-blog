<?php
/** Helps with stuff all models will use but that should be included in the class as it really doesn't really belong to the actual model
 * */

class ModelHelper
{
    protected $ReferenceUpdatesMade;
    protected $TableReferences;
    protected $TableNames;

    function __construct()
    {
        $this->TableNames = array();
        $this->TableReferences = array();
    }

    public function AddTableName($tableName, $modelName)
    {
        if(!array_key_Exists($tableName, $this->TableNames)){
            $this->TableNames[$tableName] = $modelName;
        }
    }

    public function GetModelName($tableName)
    {
        if(array_key_exists($tableName, $this->TableNames)){
            return $this->TableNames[$tableName];
        }else{
            if($tableName == ''){
                $tableName = '[Empty]';
            }
            trigger_error("Missing model entry for table $tableName", E_USER_WARNING);
        }
    }

    public function GetTableName($modelName)
    {
        foreach($this->TableNames as $key => $value){
            if($value == $modelName){
                return $key;
            }
        }

        trigger_error("Missing table entry for model $modelName", E_USER_WARNING);
    }

    public function GetModelFilePath($modelPath) {
        // Remove the file ending
        $modelPath     = str_replace(PHP_FILE_ENDING, '', $modelPath);
        $localPath     = MODEL_CACHE_FOLDER . $modelPath . MODEL_CACHE_FILE_ENDING;
        $cacheFilePath = Directory($localPath);
        return $cacheFilePath;
    }

    public function CacheModelFromModel($core, $modelName, $filePath, $dontCacheModels)
    {
        $core->GetModelFolder() . $modelName;
        $modelPath = Directory($core->GetModelFolder() . $modelName);

        require_once($modelPath);

        // Find the name of the model
        $modelName = str_replace(PHP_FILE_ENDING, '', $modelName);

        $modelInstance = new $modelName(NULL);
        $tableName     = $modelInstance->TableName;

        $coreInstanceProperty = new ReflectionProperty(CORE_CLASS, 'Instance');
        $coreInstance =  $coreInstanceProperty->getValue();

        $db = $coreInstance->GetDatabase();

        $response = $db->DescribeTable($tableName);
        if ($response == NULL) {
            trigger_error("Missing table " . $tableName, E_USER_ERROR);
        }

        // Find references to other tables
        foreach ($response['References'] as $reference) {
            $this->ReferenceUpdatesMade = true;                     // Flag for updates in some references. This will cause the program the to update already cached models be checked for new references
            $foreignTableName = $reference['TableName'];
            $this->AddTableReference($modelName, $foreignTableName, $reference);
        }

        if(!$dontCacheModels) {
            // Save the data to cache
            $saveResult = file_put_contents($filePath, json_encode($response));

            if ($saveResult == false) {
                trigger_error("Failed to save model cache " . $modelName, E_USER_WARNING);
            }
        }

        $modelCache             = &Core::$Instance->GetModelCache();
        $modelCache[$modelName] = $response;

        $this->AddTableName($response['MetaData']['TableName'], $modelName);

        return $response;
    }

    public function ReadModelCache($core, $modelName, $filePath)
    {
        $modelPath = Directory($core->GetModelFolder() . $modelName);
        require_once($modelPath);

        $modelName = str_replace(PHP_FILE_ENDING, '', $modelName);
        $buffer    = file_get_contents($filePath);

        $coreInstanceProperty = new ReflectionProperty(CORE_CLASS, 'Instance');
        $coreInstance =  $coreInstanceProperty->getValue();

        $modelCache             = &$coreInstance->GetModelCache();
        $result                 = json_decode($buffer, true);
        $modelCache[$modelName] = $result;

        $this->AddTableName($result['MetaData']['TableName'], $modelName);
        return $result;
    }

    public function SaveModelCache($filePath, $modelName, $modelData)
    {
        // Save the data to cache
        $saveResult = file_put_contents($filePath, json_encode($modelData));

        if ($saveResult == false) {
            trigger_error("Failed to save model " . $modelName, E_USER_WARNING);
        }
    }

    public function ReferencesUpdated()
    {
        return $this->ReferenceUpdatesMade;
    }

    public function DebugReferences()
    {
        var_dump($this->TableReferences);
    }

    public function CheckForReferences($modelName, &$modelCache, $pluralizer)
    {
        $tableName = $this->GetTableName($modelName);
        if(array_key_exists($tableName, $this->TableReferences)){
            foreach($this->TableReferences[$tableName] as $reference){
                $referencePluralForm = $pluralizer->Pluralize($reference['ModelName']);
                $modelCache['ReversedReferences'][$referencePluralForm] = $reference;
            }
        }
    }

    protected function AddTableReference($modelName, $tableName, $reference) {

        $reference['ModelName'] = $modelName;
        if (!array_key_exists($tableName, $this->TableReferences)) {
            $this->TableReferences[$tableName] = array();
        }

        $this->TableReferences[$tableName][] = $reference;
    }
}