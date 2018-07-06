<?php
class Project extends Model{
    public $TableName = 'project';

    public function GetLink()
    {
        return urlencode($this->Name);
    }
}