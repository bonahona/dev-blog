<?php
class HtmlHelper
{
    private $m_controller;

    public function __construct($controller){
        $this->m_controller = $controller;
    }

    public function Css($filename)
    {
        $filepath = $this->m_controller->GetCurrentCore()->GetCssFolder() . $filename;
        $result = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$filepath\"/>";

        return $result;
    }

    public function Favicon($filename)
    {
        $filepath = $this->ImageFilePath($filename);
        $result = "<link rel=\"icon\" type=\"image/png\" href=\"$filepath\"/>";
        return $result;
    }

    public function Js($filename, $isLocal = true)
    {
        if($isLocal) {
            $filepath = $this->m_controller->GetCurrentCore()->GetJsFolder() . $filename;
        }else{
            $filepath = $filename;
        }

        $result = "<script src=\"$filepath\"></script>";

        return $result;
    }

    public function Image($filename, $properties = null)
    {
        if(isset($properties['attributes'])){
            $attributes = $this->ParseAttributes($properties['attributes']);
        }else{
            $attributes = "";
        }

        $filepath = $this->ImageFilePath($filename);

        $result = "<img src=\"$filepath\" . $attributes>";
        return $result;
    }

    public function ImageFilePath($filename)
    {
        $result = $this->m_controller->GetCurrentCore()->GetImageFolder() . $filename;
        return $result;
    }

    public function Link($url, $text, $properties = null)
    {
        if(isset($properties['attributes'])){
            $attributes = $this->ParseAttributes($properties['attributes']);
        }else{
            $attributes = "";
        }

        $applicationPath = $this->ApplicationPath($url);

        $result = "<a href=\"$applicationPath\" $attributes>$text</a>";
        return $result;
    }

    public function ApplicationPath($url)
    {
        return Url($url);
    }

    public function SafeHtml($text)
    {
        $result = str_replace('<', '&#60;', $text);
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