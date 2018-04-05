<?php
/**
 * Wrapper around a model to allow for model interaction such as load and save of data to the database
 */

const INT = 'i';
const STRING = 's';

class ModelCollection implements ICollection
{
    public $ModelCache;
    public $ModelName;

    /* @return Model */
    public function Create($defaultValues = array())
    {
        $result = new $this->ModelName($this);

        if(!isset($defaultValues[$this->ModelCache['MetaData']['PrimaryKey']])){
            $defaultValues[$this->ModelCache['MetaData']['PrimaryKey']] = 0;
        }

        foreach($defaultValues as $key => $value){
            $result->$key = $value;
        }

        $result->OnLoad();

        return $result;
    }

    /* @return Model */
    public function Find($id)
    {
        $result = $this->GetInstance()->GetDatabase()->Find($this, $id);

        if($result != null) {
            $result->OnLoad();
        }

        return $result;
    }

    /* @return bool */
    public function Exists($id)
    {
        $result = $this->GetInstance()->GetDatabase()->Exists($this, $id);
        return $result;
    }

    public function Where($conditions)
    {
        $result = new SqlCollection($this, null);
        return $result->Where($conditions);

        /*
        $conditions = $this->ConvertConditions($conditions);
        $whereConditions = $conditions->GetWhereClause();
        $result = $this->GetInstance()->GetDatabase()->Where($this, $whereConditions['ConditionString'], $whereConditions['Parameters']);

        foreach($result as $entry){
            $entry->OnLoad();
        }

        return $result;
        */
    }

    public function Any($conditions)
    {
        $conditions = ConvertConditions($conditions);
        $whereConditions = $conditions->GetWhereClause();
        return $this->GetInstance()->GetDatabase()->Any($this, $whereConditions['ConditionString'], $whereConditions['Parameters']);
    }

    public function All()
    {
        $result = $this->GetInstance()->GetDatabase()->All($this);

        foreach($result as $entry){
            $entry->OnLoad();
        }

        return $result;
    }

    public function Delete($model)
    {
        return $this->GetInstance()->GetDatabase()->Delete($this, $model);
    }

    public function Clear()
    {
        $this->GetInstance()->GetDatabase()->Clear($this);
    }

    public function Save($model){
        if($model->IsSaved()){
            $this->Update($model);
        }else{
            $this->Insert($model);
        }
    }

    protected function Insert(&$model)
    {
        return $this->GetInstance()->GetDatabase()->Insert($this, $model);
    }

    protected function Update($model)
    {
        return $this->GetInstance()->GetDatabase()->Update($this, $model);
    }

    public function Add($item)
    {
        $this->Save($item);
    }

    public function InsertAt($index, $item)
    {
        trigger_error('ModelCollection does not support Insert.', E_USER_ERROR);
    }

    public function Keys()
    {
        return $this->GetInstance()->GetDatabase()->Keys($this);
    }

    public function OrderBy($field)
    {
        $result = new SqlCollection($this, null);
        return $result->OrderBy($field);
    }

    public function OrderByDescending($field)
    {
        $result = new SqlCollection($this, null);
        return $result->OrderByDescending($field);
    }

    public function Take($count)
    {

    }

    public function First()
    {
        $result = $this->GetInstance()->GetDatabase()->First($this);

        if($result != null) {
            $result->OnLoad();
        }

        return $result;
    }

    public function Copy($item)
    {
        throw new Exception("ModelCollection::Copy() not supported");
    }

    public function GetInstance()
    {
        $coreInstanceProperty = new ReflectionProperty(CORE_CLASS, 'Instance');
        $coreInstance =  $coreInstanceProperty->getValue();

        return $coreInstance;
    }
}
