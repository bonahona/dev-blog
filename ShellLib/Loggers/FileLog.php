<?php
class FileLog implements ILog
{
    public $FileHandle;

    public function Setup($config)
    {
        $this->FileHandle = null;

        if(!isset($config['Name'])){
            trigger_error('Missing FileLog name', E_USER_WARNING);
            return;
        }

        $name = $config['Name'];

        if(!isset($config['FileName'])){
            trigger_error('File logger ' . $name . ' is missing FileName atribute', E_USER_WARNING);
            return;
        }


        $fileName = $config['FileName'];
        $fileName = Directory($fileName);

        // Make sure the containing folder exists
        $fileDirectory = GetDirectoryFromFilePath($fileName);
        if(!is_dir($fileDirectory)){
            mkdir($fileDirectory, 0777, true);
        }

        // Create the file for writing with append in mind. The pointer of the fle will be at the end of its current context
        $this->FileHandle = fopen($fileName, 'a');


    }

    public function Write($data, $logLevel = LOGGING_NOTICE)
    {
        if($this->FileHandle === null || $this->FileHandle === false){
            return;
        }

        fwrite($this->FileHandle, $data . '\r\n');
    }
}