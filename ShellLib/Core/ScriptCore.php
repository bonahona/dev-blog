<?php

// Get the root, that is the current directory but two folders up
$baseDirectoryList = explode('\\', dirname(__FILE__));
$entryCount = count($baseDirectoryList);
$workingDirectory = implode(array_slice($baseDirectoryList, 0, $entryCount - 2), '/');

define('APPLICATION_ROOT',      $workingDirectory . '/');
define('APPLICATION_FOLDER',    '/Application');
define('CONFIG_FOLDER',         '/Config/');
define('MODELS_FOLDER',         '/Models/');
define('PLUGINS_FOLDER',        '/Plugins/');
define('MODEL_CACHE_FOLDER',    '/Application/Temp/Cache/Models/');
define('DATABASE_DRIVER_FOLDER','/../../ShellLib/DatabaseDrivers/');
define('PHP_FILE_ENDING', '.php');
define('MODEL_CACHE_FILE_ENDING', '.model');

define('CORE_CLASS', 'ScriptCore');

require_once('/../../ShellLib/Core/ConfigParser.php');
require_once('/../../ShellLib/Core/ModelProxy.php');
require_once('/../../ShellLib/Core/ModelProxyCollection.php');
require_once('/../../ShellLib/Core/Model.php');
require_once('/../../ShellLib/Core/Models.php');
require_once('/../../ShellLib/Core/IDatabaseDriver.php');
require_once('/../../ShellLib/Helpers/DirectoryHelper.php');
require_once('/../../ShellLib/Helpers/ModelHelper.php');
require_once('/../../ShellLib/Collections/ICollection.php');
require_once('/../../ShellLib/Collections/IDataCollection.php');
require_once('/../../ShellLib/Collections/ModelCollection.php');
require_once('/../../ShellLib/Utility/StringUtilities.php');

/* This class is really a scaled down version of the core in ShellLib, only resposible for Models and plugins (for the plugin's models)
*/
class ScriptCore
{
    // Overrides some config
    protected  $IgnoreDatabase = false;

    public static $Instance;

    protected $DatabaseConfig;          // Server information and credentials to the database to use (if any).
    protected $ModelCache;
    protected $Models;
    protected $ModelHelper;
    protected $IsPrimaryCode;

    // Used for the primary core of the application
    protected $Plugins;

    // Used for the plugins
    protected $PluginPath;
    protected $PrimaryCore;

    protected $ConfigFolder;
    protected $ModelFolder;

    public function GetIsPrimaryCore()
    {
        return $this->IsPrimaryCode;
    }

    public function &GetModelCache(){
        return $this->ModelCache;
    }

    public function &GetDatabase(){
        return $this->Database;
    }

    public function &GetModelHelper(){
        return $this->ModelHelper;
    }

    public function GetConfigFolder()
    {
        return $this->ConfigFolder;
    }

    public function GetModelFolder()
    {
        return $this->ModelFolder;
    }

    public function &GetPrimaryCore()
    {
        return $this->PrimaryCore;
    }

    public function &GetPlugins()
    {
        return $this->Plugins;
    }

    public function &GetHelpers()
    {
        return $this->Helpers;
    }

    public function &GetModels()
    {
        return $this->Models;
    }

    function __construct($subPath = null, $primaryCore = null)
    {
        if($subPath == null){
            $this->IsPrimaryCode = true;
            self::$Instance = $this;
            $this->PrimaryCore = $this;

            $this->ModelCache = array();
            $this->ModelHelper = new ModelHelper();

            $this->SetupFolders();
            if(!$this->ReadConfig()){
                trigger_error('Failed to read Database config', E_USER_WARNING);
            }

            $this->SetupDatabase();
            $this->CacheModels();

            $this->PluginPath = '';
            $this->SetupPlugins();

        }else{
            $this->IsPrimaryCode = false;
            $this->PluginPath = $subPath;
            $this->PrimaryCore = $primaryCore;

            $this->SetupPluginFolders();
            $this->CacheModels();
            $this->Database = $primaryCore->GetDatabase();
        }

        // We are now sure all models have been loaded and all plugins initialized
        if($subPath == null) {
            $this->UpdateModelReferences();
            $this->SetupModels();
        }
    }

    protected function ReadConfig()
    {
        // Read database config
        $this->DatabaseConfig = ParseConfig($this, 'DatabaseConfig.json');
        if($this->DatabaseConfig === false){
            $this->DatabaseConfig = array();
        }

        return true;
    }

    protected function SetupFolders()
    {
        $this->ConfigFolder = APPLICATION_FOLDER . CONFIG_FOLDER;
        $this->ModelFolder = APPLICATION_FOLDER . MODELS_FOLDER;
    }

    protected function SetupPluginFolders()
    {
        $this->ConfigFolder = $this->PluginPath . CONFIG_FOLDER;
        $this->ModelFolder = $this->PluginPath . MODELS_FOLDER;
    }

    protected function SetupDatabase()
    {
        if(!empty($this->DatabaseConfig)) {

            $databaseType = $this->DatabaseConfig['Database']['DatabaseType'];

            // Override the usedatabase config flag
            if($this->IgnoreDatabase){
                $this->DatabaseConfig['Database']['UseDatabase'] = false;
            }

            // Handle the provider types given
            if($databaseType == 'MySqli'){
                $databaseProviderPath = DATABASE_DRIVER_FOLDER . 'MySqliDatabase.php';
                require_once($databaseProviderPath);
                $this->Database = new MySqliDatabase($this, $this->DatabaseConfig);
            }else if($databaseType == 'PDO'){
                $databaseProviderPath = DATABASE_DRIVER_FOLDER . 'PdoDatabase.php';
                require_once($databaseProviderPath);
                $this->Database = new PdoDatabase($this, $this->DatabaseConfig);
            }else{
                trigger_error("Unknown or unsupportet database provider found in database config: $databaseType", E_USER_ERROR);
            }
        }
    }

    // Iterates over each model to make sure they are cached
    protected function CacheModels()
    {
        // Make sure the model folder exists
        $modelCacheFolder = Directory($this->ModelFolder);
        if(!is_dir($modelCacheFolder)){
            mkdir($modelCacheFolder, 0777, true);
        }

        // Make sure the cache folder and cache folders exists
        $cacheFilePath = Directory(MODEL_CACHE_FOLDER);
        if(!is_dir($cacheFilePath)) {
            mkdir($cacheFilePath, 0777, true);
        }

        $modelHelper = ScriptCore::$Instance->GetModelHelper();
        $modelFiles = GetAllFiles($modelCacheFolder);
        foreach($modelFiles as $modelFile){
            $cacheFile = $modelHelper->GetModelFilePath($modelFile);
            if(!file_exists($cacheFile)){
                $modelHelper->CacheModelFromModel($this, $modelFile, $cacheFile, false);
            }else{
                $modelHelper->ReadModelCache($this, $modelFile, $cacheFile);
            }
        }
    }

    protected function SetupModels()
    {
        $this->Models = new Models();
        $this->Models->Setup($this->ModelCache);
    }

    protected function UpdateModelReferences()
    {
        if($this->ModelHelper->ReferencesUpdated()) {
            // Only include this file if its needed
            require_once('./ShellLib/Utility/Pluralizer.php');
            $pluralizer = new Pluralizer();
            foreach(array_keys($this->ModelCache) as $modelName) {
                $this->ModelHelper->CheckForReferences($modelName, $this->ModelCache[$modelName], $pluralizer);

                $dontCacheModels = $this->DebugDontCacheModels();
                if (!$dontCacheModels) {
                    $modelFileName = $this->ModelHelper->GetModelFilePath($modelName);
                    $this->ModelHelper->SaveModelCache($modelFileName, $modelName, $this->ModelCache[$modelName]);
                }
            }
        }
    }

    public function SetupPlugins()
    {
        $this->Plugins = array();
        $pluginFolder = Directory(PLUGINS_FOLDER);
        if(!is_dir($pluginFolder)){
            mkdir($pluginFolder, 777, true);
        }
        foreach(GetAllDirectories($pluginFolder) as $plugin){
            $pluginCore = new ScriptCore(PLUGINS_FOLDER . $plugin, $this);
            $this->Plugins[] = $pluginCore;
        }
    }
}