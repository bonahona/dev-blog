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

function Directory($localPath){
    return APPLICATION_ROOT . $localPath;
}

function GetDirectoryFromFilePath($filePath)
{
    $fileDirectories = explode('/', $filePath);
    $fileDirectories = array_splice($fileDirectories, 0, count($fileDirectories) -1);

    $result = implode('/', $fileDirectories);
    return $result;
}

function ViewPath($core, $controller, $view){
    return APPLICATION_ROOT . $core->GetViewFolder() . '/' . $controller . '/' . $view . '.php';
}

function PartialViewPath($core, $view)
{
    return APPLICATION_ROOT . $core->GetPartialFolder() . '/' . $view . '.php';
}

function LayoutPath($core, $layout){
    return APPLICATION_ROOT . $core->GetLayoutFolder() . '/' .  $layout . '.php';
}