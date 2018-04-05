<?php
function ParseConfig($core, $filePath)
{
    $completeFilePath = APPLICATION_ROOT . $core->GetConfigFolder() . $filePath;
    if(!file_exists($completeFilePath)){
        return false;
    }

    $fileContent = file_get_contents($completeFilePath);
    $parsedContent = json_decode($fileContent, true);
    return $parsedContent;
}
