<?php
if(!isset($argv[1])){
    echo "Missing migration path";
    die();
}

function getLast($values){
    $count = count($values);
    if($count > 0){
        return $values[$count -1];
    }

    return null;
}

function getFirst($values){
    return $values[0];
}

function getClassName($fileName){
    if(endsWith($fileName, '.php')){
        return getFirst(explode('.', $fileName));
    }else {
        return $fileName;
    }
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);

    return $length === 0 ||
        (substr($haystack, -$length) === $needle);
}

function getFileName($fileName){
    if(!endsWith($fileName, '.php')){
        return $fileName . ' .php';
    }else {
        return $fileName;
    }
}

$fileName = $argv[1];

if($fileName === null){
    echo "No file name entered";
    die();
}

$className = getClassName($fileName);
$guid = uniqid();

$filePath = './Application/DatabaseMigrations/' . $fileName;
$fileContent = "<?php
class $className implements IDatabaseMigration
{
    public function GetUniqueName()
    {
        return '$guid';
    }

    public function GetSortOrder()
    {
        return 0;
    }

    public function Up(\$migrator)
    {        
    }

    public function Down(\$migrator)
    {
    }

    public function Seed(\$migrator)
    {
    }
}";

file_put_contents($fileName, $fileContent);