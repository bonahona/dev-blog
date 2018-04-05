<?php
class DataHelper implements Iterator, Countable, ArrayAccess
{
    protected $m_position;
    protected $m_items;

    function __construct()
    {
        $this->m_items = array();
        $this->m_position = 0;
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

    public function count()
    {
        return count($this->m_items);
    }

    public function Add($key, $item)
    {
        $this->m_items[$key] = $item;
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

    // Parses the requests element found in the post/get data to the desired model. Will ignore any data extra data the model does not contain.
    /* @return Model */
    public function Parse($element, $model)
    {
        if(isset($this->m_items[$element])){
            $result = $model->Create();

            foreach($this->m_items[$element] as $key => $value){
                // Make sure the key exists in the model we are binding to
                if(in_array($key, $model->ModelCache['MetaData']['ColumnNames']) || $key == $model->ModelCache['MetaData']['PrimaryKey']){
					if($value == 'NULL'){
						$value = null;
					}
					
                    $result->$key = $value;
                }
            }

            return $result;
        }else{
            trigger_error("Cannot parse element $element", E_USER_WARNING);
            return null;
        }
    }

    // Parses the element to a non savable custom array. Includes all data found in the form
    /* @return array() */
    public function RawParse($element)
    {
        if(isset($this->m_items[$element])){
            $result = array();
            foreach($this->m_items[$element] as $key => $value){
				if($value == 'NULL'){
					$value = null;
				}
					
                $result[$key] = $value;
            }

            return $result;

        }else{
            trigger_error("Cannot parse element $element", E_USER_WARNING);
            return null;
        }
    }

    /* @return Model */
    public function DbParse($element, $model)
    {
        if (isset($this->m_items[$element])) {
            $element = $this->m_items[$element];
            $primaryKey = $model->ModelCache['MetaData']['PrimaryKey'];

            if (isset($element[$primaryKey])) {
                $result = $model->Find($element[$primaryKey]);

                if($result != null) {
                    foreach ($element as $key => $value) {
                        // Make sure the key exists in the model we are binding to
                        if (in_array($key, $model->ModelCache['MetaData']['ColumnNames']) || $key == $model->ModelCache['MetaData']['PrimaryKey']) {
                            $result->$key = $value;
                        }
                    }
                }

                return $result;
            } else {
                return null;
            }
        }
    }

    /* @return bool */
    public function IsEmpty()
    {
        return empty($this->m_items);
    }
}