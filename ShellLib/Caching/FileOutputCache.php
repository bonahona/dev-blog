<?php
const FILE_CACHE_FILE_EDNDING = '.cache';
class FileOutputCache implements IOutputCache
{
    public $OutputCacheConfig;
    public $StorageTime;
    public $FolderName;

    public function __construct($config)
    {
        $this->OutputCacheConfig = $config;
        $this->StorageTime = $config['StorageTime'];
        $this->FolderName = $config['CacheFolder'];
    }

    public function CacheOutput($request, $httpResult)
    {
        $expires = time() + $this->StorageTime;

        $result = array(
            'Expires' => $expires,
            'HttpResult' => $httpResult
        );

        $fileLocation = $this->GetFileLocation();
        if(!is_dir($fileLocation)) {
            mkdir($fileLocation, 0777, true);
        }

        $fileName = APPLICATION_ROOT . APPLICATION_FOLDER . $this->FolderName . $this->GetKeyName($request);
        $fileContent = json_encode($result);

        file_put_contents($fileName, $fileContent);
    }

    public function GetCache($request)
    {
        if($request['MethodName'] != 'GET'){
            return null;
        }

        $fileName = $this->GetFileLocation() . $this->GetKeyName($request);
        if(!file_exists($fileName)){
            return null;
        }

        $fileContent = file_get_contents($fileName);
        $fileCache = json_decode($fileContent, true);

        if($fileCache['Expires'] < time()){
            return null;
        }

        $result = new HttpResult();
        $result->Content = $fileCache['HttpResult']['Content'];
        $result->ReturnCode = $fileCache['HttpResult']['ReturnCode'];
        $result->MimeType = $fileCache['HttpResult']['MimeType'];
        $result->Location = $fileCache['HttpResult']['Location'];

        return $result;
    }

    public function Invalidate($request)
    {

    }

    protected  function GetFileLocation()
    {
        return APPLICATION_ROOT . APPLICATION_FOLDER . $this->FolderName;
    }

    protected function GetKeyName($request)
    {
        $data = array(
            'controller-' . $request['ControllerName'],
            'action-' . $request['ActionName'],
            'variables-' . implode('-', $request['Variables'])
        );

        $result = implode('_', $data) . FILE_CACHE_FILE_EDNDING;
        return $result;
    }
}