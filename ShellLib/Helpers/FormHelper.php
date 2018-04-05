<?php

class FormHelper
{
    private $m_controller;
    private $m_currentForm = null;

    public function __construct($controller){
        $this->m_controller = $controller;
    }
    public function Start($name, $options = null)
    {
        $this->m_currentForm = $name;

        // Extract the options or use their default values
        if(isset($options['location'])) {
            $location = $options['location'];
        }else{
            $location = $this->m_controller->RequestString;
        }

        if(isset($options['method'])){
            $method = $options['method'];
        }else{
            $method = "post";
        }

        if(isset($options['attributes'])){
            $attributes = $this->ParseAttributes($options['attributes']);
        }else{
            $attributes = "";
        }

        $result = "<form id=\"$name\" method=\"$method\" action=\"$location\" $attributes>";
        return $result;
    }

    public function Input($name, $options = null)
    {
        if(!isset($options['attributes'])){
            $options['attributes'] = array();
        }

        if(isset($options['type'])){
            $type = $options['type'];
        }else{
            $type = 'text';
        }

        if(isset($options['value'])){
            $value = $options['value'];
        }else{
            $value = $this->ParseValue($name);
        }

        if(isset($options['index'])){
            $index = $options['index'];
        }else{
            $index = null;
        }

        // Passwords are not to be auto filled
        if($type == 'password'){
            $value = "";
        }else if($type == 'checkbox'){
            if($value == "1" || $value == 1){
                $options['attributes']['checked'] = 'true';
            }
            $value = '1';
        }

        if(!empty($options['attributes'])){
            $attributes = $this->ParseAttributes($options['attributes']);
        }else{
            $attributes = "";
        }

        $id = $name;
        $name = $this->ParseName($name, $index);

        if($type == 'select'){
            return $this->Select();
        }

        $result = "";
        if($type == 'checkbox'){
            $result .= "<input name=\"$name\" value=\"0\" type=\"hidden\"/>";
        }

        $result .="<input name=\"$name\" id=\"$id\" value=\"$value\" type=\"$type\" $attributes/>";
        return $result;
    }

    public function IndexedInput($name, $index, $options = null)
    {
        if($options == null){
            $options = array();
        }

        $options['index'] = $index;

        return $this->Input($name, $options);
    }

    public function Password($name, $options = null)
    {
        if($options == null){
            $options = array();
        }

        $options['type'] = 'password';
        return $this->Input($name, $options);
    }

    public function Bool($name, $options = null)
    {
        if($options == null){
            $options = array();
        }

        $options['type'] = 'checkbox';

        return $this->Input($name, $options);
    }

    public function Hidden($name, $options = null)
    {
        if($options == null){
            $options = array();
        }

        $options['type'] = 'hidden';
        return $this->Input($name, $options);
    }

    public function Area($name, $options = null)
    {
        if(isset($options['value'])){
            $value = $options['value'];
        }else{
            $value = $this->ParseValue($name);
        }

        if(isset($options['attributes'])){
            $attributes = $this->ParseAttributes($options['attributes']);
        }else{
            $attributes = "";
        }

        $id = $name;
        $name = $this->ParseName($name);

        $result = "<textarea name=\"$name\" id=\"$id\" $attributes>$value</textarea>";
        return $result;
    }
    public function Select($name, $list, $options = null)
    {
        if(!is_array($list) && !$list instanceof Collection && !$list instanceof SqlCollection){
            trigger_error("List $name is not an array nor Collection", E_USER_WARNING);
        }

        if($options == null){
            $options = array();
        }

        if(isset($options['key'])){
            $keyIndex = $options['key'];
        }else{
            $keyIndex = 'Id';
        };

        if(isset($options['value'])){
            $valueIndex = $options['value'];
        }else{
            $valueIndex = 'Value';
        }

        if(isset($options['nullfield'])){
            $useNullField = $options['nullfield'];
        }else{
            $useNullField = false;
        }

        if(isset($options['attributes'])){
            $attributes = $this->ParseAttributes($options['attributes']);
        }else{
            $attributes = "";
        }

        $value = $this->ParseValue($name);

        $id = $name;
        $name = $this->ParseName($name);
        $result = "<select id=\"$id\" name=\"$name\" $attributes>\n";


        if($useNullField){
            if(is_array($list)){
                $result .= "<option value=\"-1\">-None-</option>\n";
            }else{
                $result .= "<option value=\"NULL\" selected=\"\">-None-</option>\n";
            }
        }

        if(is_array($list)){
            foreach($list as $key => $item) {
                if ($key == $value) {
                    $result .= "<option value=\"$key\" selected=\"\">$item</option>\n";
                }else{
                    $result .= "<option value=\"$key\" >$item</option>\n";
                }
            }
        }else if($list instanceof Collection){
            foreach($list as $item) {
                $itemKey = $item->$keyIndex;
                $itemValue = $item->$valueIndex;

                if ($itemKey == $value) {
                    $result .= "<option value=\"$itemKey\" selected=\"\">$itemValue</option>\n";
                }else{
                    $result .= "<option value=\"$itemKey\" >$itemValue</option>\n";
                }
            }
        }else if($list instanceof SqlCollection){
            $list->FetchData();
            foreach($list as $item) {
                $itemKey = $item->$keyIndex;
                $itemValue = $item->$valueIndex;

                if ($itemKey == $value) {
                    $result .= "<option value=\"$itemKey\" selected=\"\">$itemValue</option>\n";
                }else{
                    $result .= "<option value=\"$itemKey\" >$itemValue</option>\n";
                }
            }
        }

        $result .= "</select>\n";
        return $result;
    }

    public function File($name, $options = null)
    {
        if(isset($options['attributes'])){
            $attributes = $this->ParseAttributes($options['attributes']);
        }else{
            $attributes = "";
        }

        $result = "<input name=\"$name\" type=\"file\" $attributes/>";
        return $result;
    }

    public function ValidationErrorFor($property, $attributes = null)
    {
        $validationErrors = $this->m_controller->ModelValidation->GetModelError($this->m_currentForm, $property);
        if(empty($validationErrors)){
            return '';
        }else{
            $attributes = $this->ParseAttributes($attributes);
            $response = '<div ' . $attributes .'><ul>';

            foreach($validationErrors as $error){
                $response .= '<li>' . $error . '</li>';
            }

            $response .= '</ul></div>';

            return $response;
        }
    }

    public function Submit($value, $options = null)
    {
        if(isset($options['attributes'])){
            $attributes = $this->ParseAttributes($options['attributes']);
        }else{
            $attributes = "";
        }

        $result = "<input type=\"submit\" value=\"$value\" $attributes/>";
        return $result;
    }

    public function End()
    {
        if($this->m_currentForm == null){
            trigger_error('No form is currently open', E_USER_WARNING);
        }else{
            $this->m_currentForm = null;
            return "</form>\n";
        }
    }

    private function ParseValue($name)
    {
        $viewData = $this->m_controller->GetViewData();

        if(isset($this->m_controller->Data[$this->m_currentForm][$name])){
            return $this->m_controller->Data[$this->m_currentForm][$name];
        }else if(isset($viewData[$this->m_currentForm])){

            $viewVar = $viewData [$this->m_currentForm];
            if(is_a($viewVar, 'Model') && $viewData[$this->m_currentForm]->HasProperty($name)) {
                return $viewVar->$name;
            }else if(is_array($viewVar)){
                return $viewVar[$name];
            }
        }else{
            return "";
        }
    }

    private function ParseName($name, $index = null)
    {
        if($index == null){
            $result = "data[$this->m_currentForm][$name]";
        }else{
            $result = "data[$this->m_currentForm][$index][$name]";
        }

        return $result;
    }

    private function ParseAttributes($attributes)
    {
        if($attributes == null){
            return '';
        };

        if(!is_array($attributes)){
            trigger_error("Attributes is not an array", E_USER_WARNING);
        }

        $attributeArray = array();
        foreach($attributes as $attribute => $value){
            $attributeArray[] = "$attribute=\"$value\"";
        }

        $result = implode($attributeArray, " ");
        return $result;
    }
}