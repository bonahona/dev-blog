<?php
class SqlCollection implements IDataCollection
{

    public $WhereCondition;
    public $AnyCondition;
    public $OrderByCondition;
    public $TakeCondition;
    public $SkipCondition;

    public $ParentQuery;
    public $SubQuery;

    private $IsExecuted;
    private $ModelCollection;

    public function __construct($modelCollection, $parentQuery)
    {
        $this->m_position = 0;
        $this->m_items = array();

        $this->ModelCollection = $modelCollection;
        $this->ParentQuery = $parentQuery;

        $this->WhereCondition = null;
        $this->AnyCondition = null;
        $this->OrderByCondition = null;
        $this->TakeCondition = null;
        $this->SkipCondition = null;
        $this->SubQuery = null;

        $this->IsExecuted = false;
    }

    public function GetModelCollection()
    {
        return $this->ModelCollection;
    }

    public function Copy($items)
    {
        foreach($items as $item){
            $this->Add($item);
        }
    }

    function rewind()
    {
        $this->FetchData();
        $this->m_position = 0;

    }

    function current()
    {
        $this->FetchData();
        return $this->m_items[$this->m_position];
    }

    function key()
    {
        $this->FetchData();
        return $this->m_position;
    }

    function next()
    {
        $this->FetchData();
        $this->m_position++;
    }

    function valid()
    {
        $this->FetchData();
        return isset($this->m_items[$this->m_position]);
    }

    function count()
    {
        $this->FetchData();
        return count($this->m_items);
    }

    public function Keys()
    {
        $this->FetchData();
        return array_keys($this->m_items);
    }

    public function Add($item)
    {
        foreach(debug_backtrace() as $stackframe){
            echo "File: " . $stackframe['file'] . " Line: " . $stackframe['line'] . "<br/>";
        }

        trigger_error('SqlCollection does not support Add. Use a selection first.', E_USER_ERROR);
    }

    public function InsertAt($index, $item)
    {
        trigger_error('SqlCollection does not support InsertAt. Use a selection first.', E_USER_ERROR);

    }

    public function First()
    {
        $this->TakeCondition = 1;
        $this->FetchData();

        if(count($this->m_items) > 0){
            return $this->m_items[0];
        }else{
            return null;
        }
    }

    public function Last()
    {
        $this->TakeCondition = 1;
        $this->FetchData();

        $tmpArray = array_reverse($this->m_items);
        if(count($tmpArray) > 0){
            return $tmpArray[0];
        }else{
            return null;
        }
    }

    public function OrderBy($field)
    {
        $this->OrderByCondition = array(
            'Field' => $field,
            'Order' => 'asc'
        );
        return $this;
    }

    public function OrderByDescending($field)
    {
        $this->OrderByCondition = array(
            'Field' => $field,
            'Order' => 'desc'
        );
        return $this;
    }

    public function Where($conditions)
    {
        if($this->IsExecuted){
            $result = new SqlCollection($this->ModelCollection, null);
            $result->Where($this->WhereCondition)->Where($conditions);
            return $result;
        }

        if($this->WhereCondition == null) {
            $this->WhereCondition = ConvertConditions($conditions);
            return $this;
        }else{
            $this->WhereCondition = CombineAndConditions($this->WhereCondition, ConvertConditions($conditions));
            return $this;
        }
    }

    public function Any($conditions)
    {
        $this->AnyCondition = $conditions;
        return false;
    }

    public function Take($count)
    {
        $this->TakeCondition = $count;
        return $this;
    }

    public function offsetSet($offset, $value)
    {
        if(is_null($offset)){
            $this->m_items[] = $value;
        }else{
            $this->m_items[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        $this->FetchData();
        return isset($this->m_items[$offset]);
    }

    public function offsetUnset($offset)
    {
        $this->FetchData();
        unset($this->m_items[$offset]);
    }

    public function offsetGet($offset)
    {
        $this->FetchData();
        if(isset($this->m_items[$offset])){
            return $this->m_items[$offset];
        }else{
            return null;
        }
    }

    public function FetchData()
    {
        if($this->IsExecuted){
            return null;
        }

        if($this->ParentQuery != null){
            return $this->ParentQuery->FetchData();
        }

        $this->IsExecuted = true;
        $database = $this->ModelCollection->GetInstance()->GetDatabase();

        $result = $database->Execute($this);
        foreach($result as $item){
            $item->OnLoad();
            $this->m_items[] = $item;
        }

        return $result;
    }
}