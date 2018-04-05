<?php

const  PHP_DOC_FOLDER = './Application/Temp/PhpDoc';
const PHP_DOC_MODEL_FILE = '/ModelDocumentation.php';
const PHP_DOC_MODEL_COLLECTION_FILE = '/ModelCollectionDocumentation.php';

class PhpDocWriter
{
    /* @var ModelHelper ModelHelper*/
    public $ModelHelper;

    public function __construct($modelHelper)
    {
        $this->ModelHelper = $modelHelper;
    }

    public function WritePhpDocForModelCollections($modelCaches)
    {
        if ($modelCaches == null) {
            return;
        }

        if(!is_dir(PHP_DOC_FOLDER)) {
            mkdir(PHP_DOC_FOLDER, 0777, true);
        }

        $result = array();
        $result = array_merge($result, $this->GetModelsPhpDocHeader());

        $result = array_merge($result, $this->WritePhpDocForModelsClass($modelCaches));

        foreach($modelCaches as $key => $modelCache){
            $result = array_merge($result, $this->WritePhpDocForModelCollection($key, $modelCache));
        }

        $fileContent = implode("\n", $result);

        file_put_contents(PHP_DOC_FOLDER . PHP_DOC_MODEL_COLLECTION_FILE, $fileContent);
    }

    private function WritePhpDocForModelsClass($modelCaches)
    {
        $result = array();

        $result[] = '/**';
        foreach ($modelCaches as $key => $modelCache) {
            $result[] = '* @property ' . $key . 'ModelCollection' . ' ' . $key;
        }

        $result[] = '**/';
        $result[] = 'class Models {}';

        $result[] = '';
        $result[] = '';

        return $result;
    }

    private function WritePhpDocForModelCollection($modelName)
    {
        $result = array();

        $modelCollectionName = $modelName . 'ModelCollection';
        $result[] = '/**';

        $result[] = '* @method ' . $modelCollectionName . ' Copy()';
        $result[] = '* @method ' . $modelCollectionName . ' OrderBy($field)';
        $result[] = '* @method ' . $modelCollectionName . ' OrderByDescending($field)';
        $result[] = '* @method ' . $modelCollectionName . ' Where($conditions)';
        $result[] = '* @method ' . $modelCollectionName . ' Take()';
        $result[] = '* @method ' . $modelCollectionName . ' All()';
        $result[] = '* @method ' . $modelName . ' First()';
        $result[] = '* @method ' . $modelName . ' Find($id)';
        $result[] = '* @method ' . $modelName . ' Create($defaults = array())';
        $result[] = '* @method bool Any()';
        $result[] = '* @method bool Exists($id)';
        $result[] = '**/';
        $result[] = 'class ' . $modelCollectionName . ' {}';

        $result[] = '';
        $result[] = '';

        return $result;
    }

    public function WritePhpDocForModels($modelCaches)
    {
        if ($modelCaches == null) {
            return;
        }

        if(!is_dir(PHP_DOC_FOLDER)) {
            mkdir(PHP_DOC_FOLDER, 0777, true);
        }

        $result = array();
        $result = array_merge($result, $this->GetModelsPhpDocHeader());

        foreach ($modelCaches as $key => $modelCache) {
            $result = array_merge($result, $this->GetPhpDocForModel($key, $modelCache));
        }

        $fileContent = implode("\n", $result);
        file_put_contents(PHP_DOC_FOLDER . PHP_DOC_MODEL_FILE, $fileContent);
    }

    private function GetModelsPhpDocHeader()
    {
        return array(
            '<?php',
            '/* This is an auto generated file from ShellMVC framework to help with code completion in some IDEs (built with  PHP Storm in mind.',
            'This file will be auto genereated each time new models is cached and all content will be overwritten.',
            'DO NOT MAKE MANUAL CHANGES TO THIS FILE AS THEY WILL BE OVERWRITTEN',
            '*/',
            '',
            ''
        );
    }

    private function GetPhpDocForModel($key, $modelCache)
    {
        $result = array();

        $result[] = '/**';
        $className = $key;
        foreach ($modelCache['Columns'] as $columnName => $column) {
            $result[] = '* @property ' . $this->GetPropertyType($column['Type']) . ' ' . $columnName;
        }

        foreach ($modelCache['References'] as $referenceName => $reference) {
            if($reference['TableName'] != '') {
                $result[] = '* @property ' . $this->ModelHelper->GetModelName($reference['TableName']) . ' ' . $this->CreateReferenceName($referenceName);
            }
        }

        foreach($modelCache['ReversedReferences'] as $referenceName => $reference){
            $result[] = '* @property Collection ' . $referenceName;
        }

        $result[] = '**/';
        $result[] = "class $className{}";

        // Adds two empty line
        $result[] = '';
        $result[] = '';

        return $result;
    }

    private function GetPropertyType($dataType)
    {
        if(strpos($dataType, 'int') !== false){
            return 'int';
        }else if(strpos($dataType, 'varchar') !== false){
            return 'string';
        }else{
            return 'mixed';
        }
    }

    protected function CreateReferenceName($columnName)
    {
        if(endsWith($columnName, 'Id')){
            return replaceLastOccurence($columnName, 'Id', '');
        } else if(endsWith($columnName, '_id')){
            return replaceLastOccurence($columnName, '_id', '');
        }else{
            return $columnName . 'Object';
        }
    }
}