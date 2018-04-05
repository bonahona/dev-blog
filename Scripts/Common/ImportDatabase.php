<?php

if(isset($argv[1])){
    $sourceFile = $argv[1];
}else{
    die("Missing source file");
}

require_once('MigrateDatabaseCore.php');
$migrateDatabaseObject = new MigrateDatabaseCore();
$migrateDatabaseObject->RunImportDatabase($sourceFile);
