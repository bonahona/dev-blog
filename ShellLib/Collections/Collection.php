<?php

// Wrapper around a group of models fetched form DB to
class Collection implements IDataCollection
{
    private $m_position;
    private $m_items;

    public function __construct()
    {
        $this->m_position = 0;
        $this->m_items = array();
    }

    public function Copy($items)
    {
        foreach($items as $item){
            $this->Add($item);
        }
    }

    public function CopyTo($collection)
    {
        foreach($this->m_items as $item){
            $collection->Add($item);
        }
    }

    function rewind()
    {
        $this->m_position = 0;
    }

    function current()
    {
        return $this->m_items[$this->m_position];
    }

    function key()
    {
        return $this->m_position;
    }

    function next()
    {
        $this->m_position++;
    }

    function valid()
    {
        return isset($this->m_items[$this->m_position]);
    }

    function count()
    {
        return count($this->m_items);
    }

    public function Keys()
    {
        return array_keys($this->m_items);
    }

    public function Add($item)
    {
        $this->m_items[] = $item;
    }

    public function AddRange($items)
    {
        foreach($items as $item)
        {
            $this->Add($item);
        }
    }

    public function InsertAt($index, $item)
    {
        $size = count($this->m_items);
        if($index >  $size){
            $this->Add($item);
            return;
        }

        $elementToMove = $size - $index;
        $this->ShiftDown($index, $elementToMove);
        $this->m_items[$index] = $item;
    }

    public function OrderBy($field)
    {
        $customObjectSorter = new CustomObjectSorter();
        return $customObjectSorter->SortCollection($this->m_items, $field);
    }

    public function OrderByDescending($field)
    {
        $customObjectSorter = new CustomObjectSorter();
        return $customObjectSorter->SortCollection($this->m_items, $field, Descending);
    }

    public function Where($conditions)
    {
        $result = new Collection();

        foreach($this->m_items as $item){
            if($this->CheckConditions($conditions, $item)){
                $result->Add($item);
            }
        }

        return $result;
    }

    public function WhereNot($conditions)
    {
        $result = new Collection();

        foreach($this->m_items as $item){
            if(!$this->CheckConditions($conditions, $item)){
                $result->Add($item);
            }
        }
        return $result;
    }

    public function Any($conditions)
    {
        foreach($this->m_items as $item){
            if($this->CheckConditions($conditions, $item)){
                return result;
            }
        }

        return false;
    }

    public function Take($count)
    {
        $result = new Collection();

        $currentCount = 0;
        foreach($this->m_items as $item){
            $result->Add($item);
            $currentCount ++;

            if($currentCount == $count){
                break;
            }
        }

        return $result;
    }

    private function CheckConditions($conditions, $item)
    {
        if(!is_array($conditions)){
            return false;
        }else {

            foreach($conditions as $key => $value){
                if($item->$key != $value){
                    return false;
                }
            }

            // Nothing failed this conditions check
            return true;
        }
    }

    public function First()
    {
        if(count($this->m_items) > 0){
            return $this->m_items[0];
        }else{
            return null;
        }
    }

    public function Last()
    {
        $tmpArray = array_reverse($this->m_items);
        if(count($tmpArray) > 0){
            return $tmpArray[0];
        }else{
            return null;
        }
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
        return isset($this->m_items[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->m_items[$offset]);
    }

    public function offsetGet($offset)
    {
        if(isset($this->m_items[$offset])){
            return $this->m_items[$offset];
        }else{
            return null;
        }
    }

    protected function ShiftDown($startIndex, $count)
    {
        if($count == 0){
            return;
        }

        for($i = $startIndex + $count; $i > $startIndex; $i --){
            $this->m_items[$i] = $this->m_items[$i -1];
        }
    }
}