<?php
class ModelValidationHelper
{
    protected $m_errors;

    public function __construct()
    {
        $this->m_errors = array();
    }

    public function &GetErrors()
    {
        return $this->m_errors;
    }

    public function GetModelError($model, $property = null)
    {
        if(!empty($this->m_errors[$model])){

            if(!empty($property)){
                if(!empty($this->m_errors[$model][$property])){
                    return $this->m_errors[$model][$property];
                }else{
                    return array();
                }
            }else{
                return array();
            }
        }
    }

    public function Valid()
    {
        return empty($this->m_errors);
    }

    public function AddError($model, $property, $errors)
    {
        if(!isset($this->m_errors[$model])){
            $this->m_errors[$model] = array();
        }

        if(!isset($this->m_errors[$model][$property])){
            $this->m_errors[$model][$property] = array();
        }

        if(is_array($errors)){
            foreach($errors as $error){
                $this->m_errors[$model][$property][] = $error;
            }
        }else {
            $this->m_errors[$model][$property][] = $errors;
        }
    }

    public function Clear()
    {
        $this->m_errors = array();
    }
}