<?php
require_once('./ShellLib/Caching/IOutputCache.php');
class Caching
{
    public $Cachers;

    public function __construct()
    {
        $this->Cachers = array();
    }

    public function AddCaching($name, $caching)
    {
        $this->Cachers[$name] = $caching;
    }

    public function CacheExists()
    {
        return count($this->Cachers) > 0;
    }

    public function GetCaching($name){
        if(isset($this->Cachers[$name])){
            return $this->Cachers[$name];
        }

        trigger_error('Tried to get cacher that does not exists: ' . $name, E_USER_ERROR);
    }

    public function GetFirstCache()
    {
        $keys = array_keys($this->Cachers);
        return $this->Cachers[$keys[0]];
    }
}