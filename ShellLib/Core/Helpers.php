<?php
class Helpers
{
    protected $m_currentController;
    protected $m_helperFiles;
    protected $m_helpers;

    function __construct()
    {
        $this->m_helperFiles = array();
        $this->m_helpers = array();
    }

    public function setCurrentController($controller)
    {
        $this->m_currentController = $controller;
    }

    public function GetHelperName($helperFileName)
    {
        $result = str_replace('Helper.php', '', $helperFileName);
        return $result;
    }

    public function GetHelperClassName($helperFileName)
    {
        $result = str_replace('.php', '', $helperFileName);
        return $result;
    }

    public function AddHelperFile($helperName, $helperFile, $helperClassName, $pluginConfig)
    {
        if(!array_key_exists($helperName, $this->m_helperFiles)){
            $this->m_helperFiles[$helperName] = array(
                'File' => $helperFile,
                'ClassName' => $helperClassName,
                'Config' => $pluginConfig
            );
            $this->m_helpers[$helperName] = null;
        }
    }

    /* @return IHelper */
    public function __get($helperName)
    {
        if(array_key_exists($helperName, $this->m_helpers)){
            if($this->m_helpers[$helperName] == null){
                $filename = $this->m_helperFiles[$helperName]['File'];
                $className = $this->m_helperFiles[$helperName]['ClassName'];
                $pluginConfig = $this->m_helperFiles[$helperName]['Config'];

                require_once($filename);
                $helperObject =  new $className();
                $helperObject->Init($pluginConfig, $this->m_currentController);
                $this->m_helpers[$helperName] = $helperObject;
            }
            return $this->m_helpers[$helperName];
        }else{
            return null;
        }
    }
}