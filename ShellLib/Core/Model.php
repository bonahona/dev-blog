<?php
class Model
{
    protected $ModelCollection;
    protected $IsSaved;                 // Is this object inserted into the database
    protected $IsDirty;                 // Has this object been changed in any way since its last save to the database
    protected $Properties;              // Properties matched in db
    protected $References;              // Model proxy objects for references
    protected $ReferenceCollections;    // List of other objects referring to this one
    protected $CustomProperties;        // Custom properties added from without the db. Wont be saved back

    /* @var Models */
    protected $Models;                  // Reference to the models object so other models can be searched for with references

    /* @var Helpers */
    protected $Helpers;                 // Reference to the helpers list

    function __construct($modelCollection)
    {
        $coreInstanceProperty = new ReflectionProperty(CORE_CLASS, 'Instance');
        $coreInstance =  $coreInstanceProperty->getValue();

        $this->Models = $coreInstance->GetModels();
        $this->Helpers = $coreInstance->GetHelpers();

        // When called the model data is being cached from db, no model collection will be sent in as it only needs the table name
        if($modelCollection == null){
            return;
        }

        $this->ModelCollection = $modelCollection;

        // Setup properties
        $this->Properties = array();
        foreach($modelCollection->ModelCache['Columns'] as $key => $column){
            $this->Properties[$column['Field']] = $column['Default'];
        }

        $this->SetupReferences();
        $this->SetupReverseReferences();

        // Create a way to handle custom properties
        $this->CustomProperties = array();

        $this->IsSaved = false;
        $this->IsDirty = false;
    }

    public function ReloadReferences()
    {
        $this->SetupReferences();
        $this->SetupReverseReferences();
    }

    // If the model entity has been created rather than loaded from DB, references and the likes needs to set manually.
    // This functions performs all neccessary operations to make the model perform just like any other loaded model enity.
    public function Load()
    {
        $this->ReloadReferences();
        $this->OnLoad();
    }

    protected function SetupReferences()
    {
        $this->References = array();
        foreach($this->ModelCollection->ModelCache['References'] as $key => $column){
            $fieldName = $this->CreateReferenceName($column['Field']);
            $modelName = $this->Models->GetModelNameForTable($column['TableName']);
            $model = $this->Models->GetModelForName($modelName);
            $this->References[$fieldName] = new ModelProxy($column['Field'], $model);
        }
    }

    protected function SetupReverseReferences()
    {
        // Setup reverse properties
        $this->ReferenceCollections = array();
        foreach($this->ModelCollection->ModelCache['ReversedReferences'] as $key => $column){
            $modelName = $this->Models->GetModelNameForTable($column['ModelName']);
            $model = $this->Models->GetModelForName($modelName);
            $this->ReferenceCollections[$key] = new ModelProxyCollection($column['Field'], $model, $column['TableColumn']);
        }
    }

    public function OnLoad()
    {
        foreach($this->References as $reference) {
            $reference->PrimaryKey = $this->Properties[$reference->FieldName];
        }

        foreach($this->ReferenceCollections as $reference){
            $localModelId = $this->Properties[$reference->LocalModelField];
            $reference->LocalModelId = $localModelId;
        }
    }

    public function Validate()
    {
        return array();
    }

    public function HasProperty($name)
    {
        return isset($this->Properties[$name]);
    }

    function FlagAsSaved()
    {
        $this->IsSaved = true;
    }

    function FlagAsClean()
    {
        $this->IsDirty = false;
    }

    /* @return mixed */
    function __get($propertyName)
    {
        if(array_key_exists($propertyName, $this->Properties)) {
            return $this->Properties[$propertyName];
        }else if(array_key_exists($propertyName, $this->References)) {
            if($this->References[$propertyName]->Object == null){
                $this->References[$propertyName]->Load();
            }
            return $this->References[$propertyName]->Object;
        } else if(array_key_exists($propertyName, $this->ReferenceCollections)){
            if($this->ReferenceCollections[$propertyName]->Collection == null){
                $this->ReferenceCollections[$propertyName]->Load();
            }
            return $this->ReferenceCollections[$propertyName]->Collection;
        } else if(array_key_exists($propertyName, $this->CustomProperties)){
            return $this->CustomProperties[$propertyName];
        }else{
            return null;
        }
    }

    function __set($propertyName, $value)
    {
        if(array_key_exists($propertyName, $this->Properties)){
            $this->Properties[$propertyName] = $value;
            $this->IsDirty = true;
        }else{
            $this->CustomProperties[$propertyName] = $value;
        }
    }

    /* @return bool */
    public function IsSaved()
    {
        return $this->IsSaved;
    }

    /* @return bool */
    public function IsDirty()
    {
        return $this->IsDirty;
    }

    /* @return Model */
    public function Save()
    {
        $this->ModelCollection->Save($this);

        // In case the Primary key has changed, the references key need an update
        foreach($this->References as $reference) {
            $reference->PrimaryKey = $this->Properties[$reference->FieldName];
        }

        $this->FlagAsSaved();
        $this->FlagAsClean();

        return $this;
    }

    public function Delete()
    {
        $this->ModelCollection->Delete($this);
    }

    public function Object()
    {
        $result = array();

        foreach($this->Properties as $key => $value){
            $result[$key] = $value;
        }

        foreach($this->References as $key => $value){
            $result[$key] = 'Proxy Object';
        }

        foreach($this->ReferenceCollections as $key => $value){
            $result[$key] = 'Proxy Collection';
        }

        foreach($this->CustomProperties as $key => $value){
            $result[$key] = $value;
        }

        return $result;
    }

    public function References()
    {
        $result = array();

        foreach($this->References as $key => $value){
            $result[] = $key;
        }

        return $result;
    }

    public function ReverseReferences()
    {
        $result = array();

        foreach($this->ReferenceCollections as $key => $value){
            $result[] = $key;
        }

        return $result;
    }

    // TODO: This function should lie somewhere else. Duplicate in PhpDocWriter
    protected function CreateReferenceName($columnName)
    {
        if(endsWith($columnName, 'Id')){
            return replaceLastOccurence($columnName, 'Id', '');
        } else if(endsWith($columnName, '_id')){
            return replaceLastOccurence($columnName, '_id', '');
        }else{
            return $columnName + 'Object';
        }
    }

    public function ConvertZeroToNull()
    {
        foreach($this->Properties as $key => $property){
            if($property === 0 || $property === '0'){
                $this->$key = null;
            }
        }
    }

    public function ConvertNullToNull()
    {
        foreach($this->Properties as $key => $property){
            if($property === 'NULL' || $property === 'null'){
                $this->$key = null;
            }
        }
    }
}