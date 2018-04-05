<?php
// Class built to act as a proxy for models containing foreign keys to other objects.
// This is built to whenever a model with references is loaded it does not have to actually load all other objects from db
// but their info is stored so they can be loaded upon request
class ModelProxy
{
    public $FieldName;
    public $PrimaryKey;
    public $Model;
    public $Object;

    function __construct($fieldName, $model)
    {
        $this->FieldName = $fieldName;
        $this->PrimaryKey = null;
        $this->Model = $model;
        $this->Object = null;
    }

    public function Load()
    {
        if($this->PrimaryKey == null || $this->Model == null){
            return;
        }

        if($this->Object == null){
            $this->Object = $this->Model->Find($this->PrimaryKey);
        }
    }
}