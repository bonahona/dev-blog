<?php

function GetAllDirectories($directory)
{
    if(!is_dir($directory)){
        return array();
    }

    $directoryIgnores = array('.', '..');       // Used to filter out rhe current dir and parent dir from any directory when iterating

    $allFiles = scandir($directory);
    $allValidFiles = array_diff($allFiles, $directoryIgnores);

    $result = array();
    foreach($allValidFiles as $file){
        $filepath = $directory . $file;
        if(is_dir($filepath)){
            $result[] = $file;
        }
    }

    return $result;
}

function GetAllFiles($directory)
{
    if(!is_dir($directory)){
        return array();
    }

    $directoryIgnores = array('.', '..');       // Used to filter out rhe current dir and parent dir from any directory when iterating

    $allFiles = scandir($directory);
    $allValidFiles = array_diff($allFiles, $directoryIgnores);

    $result = array();
    foreach($allValidFiles as $file){
        $filepath = $directory . $file;
        if(!is_dir($filepath)){
            $result[] = $file;
        }
    }

    return $result;
}

function Directory($localPath)
{
    return APPLICATION_ROOT . $localPath;
}

function GetDirectoryFromFilePath($filePath)
{
    $fileDirectories = explode('/', $filePath);
    $fileDirectories = array_splice($fileDirectories, 0, count($fileDirectories) -1);

    $result = implode('/', $fileDirectories);
    return $result;
}

function ViewPath($core, $controller, $view)
{
    return APPLICATION_ROOT . $core->GetViewFolder() . '/' . $controller . '/' . $view . '.php';
}

function PartialViewPath($core, $view)
{
    return APPLICATION_ROOT . $core->GetPartialFolder() . '/' . $view . '.php';
}

function LayoutPath($core, $layout)
{
    return APPLICATION_ROOT . $core->GetLayoutFolder() . '/' .  $layout . '.php';
}

function CreateArray($value, $count)
{
    $result = array();

    for($i = 0; $i < $count; $i++) {
        $result[] = $value;
    }

    return $result;
}

function RemoveEmpty($subject)
{
    $result = array();
    foreach($subject as $entry){
        if($entry != ''){
            $result[] = $entry;
        }
    }

    return $result;
}

function startsWith($haystack, $needle)
{
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function endsWith($haystack, $needle)
{
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function replaceLastOccurence($subject, $search, $replace)
{
    $pos = strrpos($subject, $search);

    if($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}

function ArrayKeyExistsCaseInsensitive($needle, $haystack)
{
    foreach(array_keys($haystack) as $key){
        if(strtolower(($key) == strtolower($needle))){
            return true;
        }
    }

    return false;
}

function CheckCapabilities($allowedCapabilities, $capabilities)
{
    foreach($allowedCapabilities as $allowedCapability){
        if(in_array($allowedCapability, $capabilities)){
            return true;
        }
    }

    return false;
}