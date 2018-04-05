<?php

// Second variable will be the current directory the user runs the scripts from
$outputDirectory = $argv[1];

// The second variable in will be the scripts own first parameter. Should be the desired output file
// If this is not present, a default timestamp file will be created instead

if(isset($argv[2])){
    $outputFile = $argv[2];
}else{
    $outputFile = date('Y-m-d_g-i-s') . '.json';
}

$outputPath = $outputDirectory . '/'. $outputFile;

require_once('MigrateDatabaseCore.php');
$migrateDatabaseObject = new MigrateDatabaseCore();
$migrateDatabaseObject->RunExportDatabase($outputPath);
