<?php
// Class for containing a collection of other objects.
// These are created by other classes having a multiple type reference to this one
class ModelProxyCollection
{
    public $LocalModelField;
    public $LocalModelId;
    public $ForeignKey;
    public $Model;
    public $Collection;

    function __construct($foreignKey, $model, $localModelField)
    {
        $this->LocalModelField = $localModelField;
        $this->ForeignKey = $foreignKey;
        $this->Model = $model;
        $this->Collection = null;
    }

    public function Load()
    {
        if($this->ForeignKey == null || $this->Model == null || $this->LocalModelId == null){
            return;
        }

        if($this->Collection == null){
            $this->Collection = $this->Model->Where(array($this->ForeignKey => $this->LocalModelId));
        }
    }
}