<?php
class DatabaseColumn
{
    public $Name;
    public $Type;
    public $Special;
    public $References;

    public function __construct($name, $type, $special, $references)
    {
        $this->Name = $name;
        $this->Type = $type;
        $this->Special = $special;
        $this->References = $references;
    }
}