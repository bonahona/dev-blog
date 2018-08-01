<?php

// Setup the ServerRoot and Application root differently if the Core is invoked from the command line or from a webserver
if(php_sapi_name() === 'cli'){
    // Get the root, that is the current directory but two folders up.
    // Windows uses backslases ('\') while UNIX systems uses normals forward slashes ('/').
    if (strpos(dirname(__FILE__), '\\') !== false) {
        $baseDirectoryList = explode('\\', dirname(__FILE__));
    }else{
        $baseDirectoryList = explode('/', dirname(__FILE__));
    }

    $entryCount = count($baseDirectoryList);
    $workingDirectory = implode(array_slice($baseDirectoryList, 0, $entryCount - 2), '/');

    define('SERVER_ROOT', $workingDirectory);
    define('APPLICATION_ROOT',      $workingDirectory . '/');
    chdir(APPLICATION_ROOT);
}else {
    define('SERVER_ROOT', str_ireplace('/Index.php', '', $_SERVER['PHP_SELF']));
    define('APPLICATION_ROOT', $_SERVER['DOCUMENT_ROOT']);
}

const APPLICATION_FOLDER =              '/Application';
const CONFIG_FOLDER =                   '/Config/';
const CONTROLLER_FOLDER =               '/Controllers/';
const MODELS_FOLDER =                   '/Models/';
const PLUGINS_FOLDER =                  '/Plugins/';
const HELPERS_FOLDER =                  '/Helpers/';
const VIEWS_FOLDER =                    '/Views/';
const PARTIAL_FOLDER =                  '/Views/Partial/';
const LAYOUTS_FOLDER =                  '/Views/Layouts';
const MODEL_CACHE_FOLDER =              '/Application/Temp/Cache/Models/';
const VIEW_CACHE_FOLDER =               '/Application/Temp/Cache/Views/';
const CSS_FOLDER =                      '/Content/Css/';
const JS_FOLDER =                       '/Content/Js/';
const IMAGE_FOLDER =                    '/Content/Images/';
const DATABASE_DRIVER_FOLDER =          './ShellLib/DatabaseDrivers/';
const LOGGER_FOLDER =                   '/Loggers/';
const SHELL_LIB_LOGGERS_FOLDER =        '/ShellLib/Loggers/';
const SHELL_LIB_CACHE_FOLDER =          './ShellLib/Caching/';
const SHELL_LIB_OUTPUT_CACHE_FOLDER =   '/ShellLib/OutputCaches/';
const DATABASE_MIGRATIONS_FOLDER =      '/DatabaseMigrations/';

const VIEW_FILE_ENDING =                '.php';
const MODEL_CACHE_FILE_ENDING =         '.model';
const OUTPUT_CACHE_FILE_ENDING =        '.output';
const PHP_FILE_ENDING =                 '.php';
const CSS_FILE_ENDING =                 '.css';
const JS_FILE_ENDING =                  '.js';

const CORE_CLASS =                      'Core';

const CAPABILITIES_NONE =               0;
const CAPABILITIES_CACHING =            1;
const CAPABILITIES_MODELS =             2;
const CAPABILITIES_MODEL_CACHING =      3;
const CAPABILITIES_DATABASE =           4;
const CAPABILITIES_ROUTING =            5;
const CAPABILITIES_PLUGINS =            6;
const CAPABILITIES_REQUEST =            500;        // The default capabilities needed for a web request to pass
const CAPABILITIES_ALL =                1000;

const MIGRATION_UP =                1;
const MIGRATION_DOWN =              2;
const MIGRATION_SEED =              3;

// The only classes always needed are these. The rest are loaded on demand
require_once('./ShellLib/Core/ConfigParser.php');
require_once('./ShellLib/Core/Routing.php');
require_once('./ShellLib/Core/HttpResult.php');
require_once('./ShellLib/Helpers/CoreHelper.php');
require_once('./ShellLib/Caching/Caching.php');

// External reference to the core instance
class Core
{
    public static $Instance;

    protected $ApplicationConfig;       // Generic application config. Applications and plugins are free to change this to their needs.
    protected $DatabaseConfig;          // Server information and credentials to the database to use (if any).
    protected $RoutesConfig;            // Contains hardcoded non-conventional routes aliases.
    protected $About;                   // About file is only loaded if any feature requiring it is called.

    protected $Logging;
    protected $Caching;
    protected $ModelCache;
    protected $Models;
    protected $Helpers;
    protected $ModelHelper;
    protected $FullUrl;
    protected $RequestUrl;
    protected $RequestString;
    protected $Database;
    protected $Controller;

    protected $IsPrimaryCore;

    // Used for the primary core of the application
    protected $Plugins;

    // Used for the plugins
    protected $PluginPath;
    protected $PrimaryCore;

    protected $ConfigFolder;
    protected $ModelFolder;
    protected $HelperFolder;
    protected $ControllerFolder;
    protected $ViewFolder;
    protected $PartialFolder;
    protected $LayoutFolder;
    protected $CssFolder;
    protected $JsFolder;
    protected $ImageFolder;
    protected $LoggerFolder;
    protected $CacheFolder;
    protected $DatabaseMigrationFolder;

    public function GetIsPrimaryCore()
    {
        return $this->IsPrimaryCore;
    }

    public function &GetModelCache(){
        return $this->ModelCache;
    }

    public function &GetDatabase(){
        return $this->Database;
    }

    public function &GetLogging(){
        return $this->Logging;
    }

    public function &GetCaching(){
        return $this->Caching;
    }

    /* @return ModelHelper*/
    public function &GetModelHelper(){
        return $this->ModelHelper;
    }

    /* @return string*/
    public function GetRequestUrl(){
        return $this->RequestUrl;
    }

    /* @return string*/
    public function GetFullUrl(){
        return $this->FullUrl;
    }

    public function GetConfigFolder()
    {
        return $this->ConfigFolder;
    }

    public function GetModelFolder()
    {
        return $this->ModelFolder;
    }

    public function GetHelperFolder()
    {
        return $this->HelperFolder;
    }

    public function &GetController()
    {
        return $this->Controller;
    }

    public function GetControllerFolder()
    {
        return $this->ControllerFolder;
    }

    public function GetViewFolder()
    {
        return $this->ViewFolder;
    }

    public function GetPartialFolder()
    {
        return $this->PartialFolder;
    }

    public function GetLayoutFolder()
    {
        return  $this->LayoutFolder;
    }

    public function GetCssFolder()
    {
        return  $this->CssFolder;
    }

    public function GetJsFolder()
    {
        return  $this->JsFolder;
    }

    public function GetImageFolder()
    {
        return  $this->ImageFolder;
    }

    public function GetDatabaseMigrationFolder()
    {
        return $this->DatabaseMigrationFolder;
    }

    public function GetApplicationConfig()
    {
        return $this->ApplicationConfig;
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

    // Creates the core object that is used for the application and any plugins that are included
    // SubPath is used when a plugin is created where the path supplied points out the relative path to the plugin (For model and controller inclusions
    // Creates the core object that is used for the application and any plugins that are included
    // SubPath is used when a plugin is created where the path supplied points out the relative path to the plugin (For model and controller inclusions
    function __construct($subPath = null, $primaryCore = null)
    {
        if ($subPath == null) {
            $this->IsPrimaryCore = true;
            self::$Instance = $this;
            $this->PrimaryCore = $this;

            $this->SetupPreRequestFolder();

            if (!$this->ReadConfig()) {
                trigger_error("Failed to read ApplicationConfig", E_USER_ERROR);
            }

            $this->PluginPath = '';
            $this->CreatePlugins();
            $this->SetupCaching();
        } else {
            $this->IsPrimaryCore = false;
            $this->PluginPath = $subPath;
            $this->PrimaryCore = $primaryCore;

            $this->SetupPluginConfigFolder();
        }
    }

    public function LoadCodeBase($capabilities)
    {
        require_once('./ShellLib/Core/Controller.php');
        require_once('./ShellLib/Core/ModelProxy.php');
        require_once('./ShellLib/Core/ModelProxyCollection.php');
        require_once('./ShellLib/Core/Model.php');
        require_once('./ShellLib/Core/IDatabaseDriver.php');
        require_once('./ShellLib/Core/Models.php');
        require_once('./ShellLib/Core/Helpers.php');
        require_once('./ShellLib/Core/IHelper.php');
        require_once('./ShellLib/Core/DatabaseWhereCondition.php');
        require_once('./ShellLib/Core/CustomObjectSorter.php');
        require_once('./ShellLib/Files/File.php');
        require_once('./ShellLib/Logging/Logging.php');
        require_once('./ShellLib/Helpers/ModelHelper.php');
        require_once('./ShellLib/Helpers/UrlHelper.php');
        require_once('./ShellLib/Helpers/FormHelper.php');
        require_once('./ShellLib/Helpers/ModelValidationHelper.php');
        require_once('./ShellLib/Helpers/DataHelper.php');
        require_once('./ShellLib/Helpers/SessionHelper.php');
        require_once('./ShellLib/Helpers/HtmlHelper.php');

        require_once('./ShellLib/Collections/ICollection.php');
        require_once('./ShellLib/Collections/IDataCollection.php');
        require_once('./ShellLib/Collections/Collection.php');
        require_once('./ShellLib/Collections/ModelCollection.php');
        require_once('./ShellLib/Collections/SqlCollection.php');
    }

    function SetupCore($subPath = null, $primaryCore = null, $capabilities = array())
    {
        if($subPath == null){
            $this->ModelCache = array();
            $this->ModelHelper = new ModelHelper();

            // Logging
            $this->Logging = new Logging();
            $this->FindShellLibLoggers();
            $this->FindLoggers($this->LoggerFolder);

            $this->Helpers = new Helpers();
            $this->SetupHelpers();
            $this->SetupLogs();
            $this->SetupDatabase();
            if(CheckCapabilities(array(CAPABILITIES_MODEL_CACHING, CAPABILITIES_REQUEST, CAPABILITIES_ALL), $capabilities)) {
                $this->CacheModels();
            }
        }else{
            $this->IsPrimaryCore = false;
            $this->PluginPath = $subPath;
            $this->PrimaryCore = $primaryCore;

            $this->SetupPluginFolders();
            $this->Logging = $primaryCore->GetLogging();
            $this->FindLoggers($this->LoggerFolder);

            $this->ReadPluginConfig();

            $this->FindLoggers($this->LoggerFolder);

            if(CheckCapabilities(array(CAPABILITIES_MODEL_CACHING, CAPABILITIES_REQUEST, CAPABILITIES_ALL), $capabilities)) {
                $this->CacheModels();
            }

            $this->Database = $primaryCore->GetDatabase();
            $this->Helpers = $primaryCore->GetHelpers();
            $this->SetupHelpers();
        }
    }

    protected function SetupCapabilities($capabilities = array(CAPABILITIES_ALL))
    {
        $this->LoadCodeBase($capabilities);
        $this->SetupFolders();
        $this->SetupCore('', $this, $capabilities);

        if(CheckCapabilities(array(CAPABILITIES_PLUGINS, CAPABILITIES_REQUEST, CAPABILITIES_ALL), $capabilities)){
            $this->SetupPlugins($capabilities);
        }

        if(CheckCapabilities(array(CAPABILITIES_MODELS, CAPABILITIES_REQUEST, CAPABILITIES_ALL), $capabilities)) {
            $this->SetupModels();
        }
    }

    protected  function SetupPreRequestFolder()
    {
        $this->ConfigFolder = APPLICATION_FOLDER . CONFIG_FOLDER;
    }

    protected  function SetupPluginConfigFolder()
    {
        $this->ConfigFolder = $this->PluginPath . CONFIG_FOLDER;
    }

    protected function SetupFolders()
    {
        $this->ConfigFolder = APPLICATION_FOLDER . CONFIG_FOLDER;
        $this->ModelFolder = APPLICATION_FOLDER . MODELS_FOLDER;
        $this->HelperFolder = APPLICATION_FOLDER . HELPERS_FOLDER;
        $this->ControllerFolder = APPLICATION_FOLDER . CONTROLLER_FOLDER;
        $this->ViewFolder = APPLICATION_FOLDER . VIEWS_FOLDER;
        $this->PartialFolder = APPLICATION_FOLDER . PARTIAL_FOLDER;
        $this->LayoutFolder = APPLICATION_FOLDER . LAYOUTS_FOLDER;
        $this->CssFolder = SERVER_ROOT . APPLICATION_FOLDER . CSS_FOLDER;
        $this->JsFolder = SERVER_ROOT . APPLICATION_FOLDER . JS_FOLDER;
        $this->ImageFolder = SERVER_ROOT . APPLICATION_FOLDER . IMAGE_FOLDER;
        $this->LoggerFolder = APPLICATION_FOLDER . LOGGER_FOLDER;
        $this->DatabaseMigrationFolder = APPLICATION_FOLDER . DATABASE_MIGRATIONS_FOLDER;
    }

    protected function SetupPluginFolders()
    {
        $this->ConfigFolder = $this->PluginPath . CONFIG_FOLDER;
        $this->ModelFolder = $this->PluginPath . MODELS_FOLDER;
        $this->HelperFolder = $this->PluginPath . HELPERS_FOLDER;
        $this->ControllerFolder = $this->PluginPath . CONTROLLER_FOLDER;
        $this->ViewFolder = $this->PluginPath . VIEWS_FOLDER;
        $this->PartialFolder = $this->PluginPath . PARTIAL_FOLDER;
        $this->LayoutFolder = $this->PluginPath . LAYOUTS_FOLDER;
        $this->CssFolder =  $this->PluginPath . CSS_FOLDER;
        $this->JsFolder =  $this->PluginPath . JS_FOLDER;
        $this->ImageFolder =  $this->PluginPath . IMAGE_FOLDER;
        $this->LoggerFolder = $this->PluginPath . LOGGER_FOLDER;
        $this->DatabaseMigrationFolder = $this->PluginPath . DATABASE_MIGRATIONS_FOLDER;
    }

    protected function ReadConfig()
    {
        // Read the application config
        $this->ApplicationConfig = ParseConfig($this, 'ApplicationConfig.json');
        if($this->ApplicationConfig === false){
            return false;
        }

        // Read database config
        $this->DatabaseConfig = ParseConfig($this, 'DatabaseConfig.json');
        if($this->DatabaseConfig === false){
            $this->DatabaseConfig = array();
        }

        // Read the routes config
        $this->RoutesConfig = ParseConfig($this, 'Routes.json');
        if($this->RoutesConfig === false) {
            $this->RoutesConfig = array();
        }

        return true;
    }

    protected function ReadPluginConfig()
    {
        $this->ApplicationConfig = ParseConfig($this, 'PluginConfig.json');
    }

    protected function SetupLogs()
    {
        if(!$this->Logging->SetupLoggers($this->ApplicationConfig)){
            trigger_error("Failed to setup logging", E_USER_ERROR);
        }
    }

    protected function SetupCaching()
    {
        $this->Caching = new Caching();

        if(!isset($this->ApplicationConfig['Caching'])){
            return;
        }

        foreach($this->ApplicationConfig['Caching'] as $type => $caching){

            $cacheTypeFileName = SHELL_LIB_CACHE_FOLDER . $type . PHP_FILE_ENDING;
            if(!file_exists($cacheTypeFileName)){
                trigger_error('Failed to find cache file ' . $cacheTypeFileName, E_USER_ERROR);
                continue;
            }

            require_once($cacheTypeFileName);
            if(!class_exists($type)){
                trigger_error('Missing output cache class ' . $type);
                continue;
            }

            $name = $caching['Name'];
            $outputCache = new $type($caching);
            $this->Caching->AddCaching($name, $outputCache);
        }
    }

    protected function SetupDatabase()
    {
        if(!empty($this->DatabaseConfig)) {

            $databaseType = $this->DatabaseConfig['Database']['DatabaseType'];

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
                trigger_error("Unknown database provider type: $databaseType", E_USER_ERROR);
            }
        }
    }

    protected function CapitalizeActionName()
    {
        // Read debug data from the log
        $capitalizeActionName = false;
        if($this->ApplicationConfig !== false) {
            if (array_key_exists('Application', $this->ApplicationConfig)) {
                if (array_key_exists('CapitalizeActionName', $this->ApplicationConfig['Application'])) {
                    $capitalizeActionName = $this->ApplicationConfig['Application']['CapitalizeActionName'];
                }
            }
        }
        return $capitalizeActionName;
    }

    protected function CapitalizeControllerName()
    {
        // Read debug data from the log
        $capitalizeControllerName = false;
        if($this->ApplicationConfig !== false) {
            if (array_key_exists('Application', $this->ApplicationConfig)) {
                if (array_key_exists('CapitalizeControllerName', $this->ApplicationConfig['Application'])) {
                    $capitalizeControllerName = $this->ApplicationConfig['Application']['CapitalizeControllerName'];
                }
            }
        }
        return $capitalizeControllerName;
    }

    protected function DebugDontCacheModels()
    {
        // Read debug data from the log
        $dontCacheModels = false;
        if($this->ApplicationConfig !== false) {
            if (array_key_exists('Debug', $this->ApplicationConfig)) {
                if (array_key_exists('DontCacheModels', $this->ApplicationConfig['Debug'])) {
                    $dontCacheModels = $this->ApplicationConfig['Debug']['DontCacheModels'];
                }
            }
        }

        return $dontCacheModels;
    }

    protected function DebugDieOnRoutingError()
    {
        // Read debug data from the log
        $dieOnRoutingError = false;
        if($this->ApplicationConfig !== false) {
            if (array_key_exists('Debug', $this->ApplicationConfig)) {
                if (array_key_exists('DieOnRoutingError', $this->ApplicationConfig['Debug'])) {
                    $dieOnRoutingError = $this->ApplicationConfig['Debug']['DieOnRoutingError'];
                }
            }
        }

        return $dieOnRoutingError;
    }

    // Find the logger classes available in the default shell lib folders
    protected  function FindShellLibLoggers()
    {
        $shellLibLoggerFolder = Directory(SHELL_LIB_LOGGERS_FOLDER);

        $loggerFiles = GetAllFiles($shellLibLoggerFolder);
        foreach($loggerFiles as $loggerFile){
            $this->GetLogging()->AddAvailableLogger($loggerFile, $shellLibLoggerFolder . $loggerFile);
        }
    }

    // Find loggers distributed in the application folder or in plugins
    protected  function FindLoggers($loggerDirectoryName)
    {
        $loggerDirectory = Directory($loggerDirectoryName);
        // If the folder does not exists, what's the point of looking through it?
        if(!is_dir($loggerDirectory)){
            return;
        }

        $loggerFiles = GetAllFiles($loggerDirectory);
        foreach($loggerFiles as $loggerFile){
            $this->GetLogging()->AddAvailableLogger($loggerFile, $loggerDirectory . $loggerFile);
        }
    }

    // Iterates over each model to make sure they are cached
    protected function CacheModels()
    {
        // Read debug data from the log
        $dontCacheModels = $this->DebugDontCacheModels();

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

        $modelHelper = Core::$Instance->GetModelHelper();

        $modelFiles = GetAllFiles($modelCacheFolder);
        foreach($modelFiles as $modelFile){
            $cacheFile = $modelHelper->GetModelFilePath($modelFile);
            if(!file_exists($cacheFile)){
                $modelHelper->CacheModelFromModel($this, $modelFile, $cacheFile, $dontCacheModels);
            }else{
                $modelHelper->ReadModelCache($this, $modelFile, $cacheFile);
            }
        }
    }

    protected function SetupModels()
    {
        $this->UpdateModelReferences();

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

            // Write a PhpDocFile
            require_once('./ShellLib/PhpDocWriter/PhpDocWriter.php');
            $phpDocWriter = new PhpDocWriter($this->ModelHelper);
            $phpDocWriter->WritePhpDocForModels($this->ModelCache);
            $phpDocWriter->WritePhpDocForModelCollections($this->ModelCache);

        }
    }

    public function SetupHelpers()
    {
        $helperFileDirectory = Directory($this->HelperFolder);
        $helperFiles = GetAllFiles($helperFileDirectory);

        foreach($helperFiles as $helperFile){
            $helperFileName = $helperFileDirectory . $helperFile;
            $helperName = $this->Helpers->GetHelperName($helperFile);
            $helperClassName = $this->Helpers->GetHelperClassName($helperFile);
            $this->Helpers->AddHelperFile($helperName, $helperFileName, $helperClassName, $this->ApplicationConfig);
        }
    }

    public function ParseRequest()
    {
        // Find the current request folder
        $requestRoot = $_SERVER['SCRIPT_NAME'];
        $this->RequestString = $_SERVER['REQUEST_URI'];
        $this->RequestUrl = $this->FixRequestUrl($this->RequestString);
        $this->FullUrl = $this->CreateFullUrl();

        $routingEngine = new Routing($this->RoutesConfig);
        $requestData = $routingEngine->ParseUrl($requestRoot, $this->RequestUrl);

        // Check if a cache entry is present and if that is so, just return it and don't put up the rest of the application
        if ($this->Caching->CacheExists()) {
            $caching = $this->Caching->GetFirstCache();

            $cacheResult = $caching->GetCache($requestData);
            if($cacheResult != null) {
                $this->DisplayResult($cacheResult);
                return;
            }
        }

        $this->ReturnNonCachedRequest($requestData);
    }

    public function ReturnNonCachedRequest($requestData)
    {
        // Now we need to setup the rest of the application
        $this->SetupCapabilities(array(CAPABILITIES_REQUEST));

        $variables = array();

        if($requestData != null) {
            $controllerName = $requestData['ControllerName'];
            $actionName     = $requestData['ActionName'];
            $variables      = $requestData['Variables'];

            $handler = $this->CreateHandler($controllerName, $actionName, $requestData);
        }else{
            $handler = array(
                'error' => 1,
                'message' => 'Routing engine could not map request to a configured route'
            );
        }

        // The controller or the action does not exists. If debugging is on, die and give an error, otherwise reroute to the notFound route
        if($handler['error'] == 1){
            $dieOnRoutingError = $this->DebugDieOnRoutingError();
            if($dieOnRoutingError){
                trigger_error($handler['message'], E_USER_ERROR);
            }else{
                $notFoundHandler = $this->CreateNotFoundHandler($requestData);

                // If the not found handler is not found or has en error, there is not much to do
                if($notFoundHandler['error'] == 1){
                    trigger_error('NotFoundHandler: ' . $notFoundHandler['message'], E_USER_ERROR);
                }

                $controller = $notFoundHandler['controller'];
                $actionName = $notFoundHandler['actionName'];
            }
        }else{
            $controller = $handler['controller'];
        }

        $this->ParseData($controller);

        // Call the action and validate its result
        $beforeActionHttpResult = $controller->BeforeAction();
        if($beforeActionHttpResult != null){
            if(is_a($beforeActionHttpResult, 'HttpResult')){
                $this->DisplayResult($beforeActionHttpResult);
                die();
            }
        }

        $httpResult = call_user_func_array(array($controller, $actionName), $variables);

        if($httpResult == null){
            trigger_error('Called action ' . $controllerName . '->' . $actionName . ' does return null', E_USER_ERROR);
        } else if(!is_a($httpResult, 'HttpResult')){
            trigger_error('Called action ' . $controllerName . '->' . $actionName . ' does not resturn a HttpResult object', E_USER_ERROR);
        }

        // 404 errors use the notFound route specified in the application config
        if($httpResult->ReturnCode === 404){
            $notFoundHandler = $this->CreateNotFoundHandler($requestData);

            if($notFoundHandler['error'] == 1) {
                trigger_error('NotFoundHandler: ' . $notFoundHandler['message'], E_USER_ERROR);
            }else{
                $notFoundController = $notFoundHandler['controller'];
                $notFoundAction = $notFoundHandler['actionName'];

                $controller->BeforeAction();
                $httpResult = call_user_func_array(array($notFoundController, $notFoundAction), array());
            }
        }

        $this->DisplayResult($httpResult);

        $this->CacheResult($requestData, $httpResult);

        // Clean up
        if($this->Database != null && $this->Database != false) {
            $this->Database->Close();
        }
    }

    public function CacheResult($request, $httpResult)
    {
        if($this->Caching->CacheExists($request)){
            $this->Caching->GetFirstCache()->CacheOutput($request, $httpResult);
        }
    }

    public  function DisplayResult($httpResult)
    {
        // Redirects needs to be handled first
        if($httpResult->Location != null){
            header('Location: ' . $httpResult->Location, true, $httpResult->ReturnCode);
        }

        // Set the HTTP return code (Default 200 = HTTP_OK)
        if(function_exists('http_response_code')) {
            http_response_code($httpResult->ReturnCode);
        }

        // Set the mime type of the request (Default is text/plain, standard for webpages are text/html and for json its application/json
        header('Content-Type: ' . $httpResult->MimeType);

        echo $httpResult->Content;
    }

    // Takes the raw request url and makes sure it follows the expected format
    public function FixRequestUrl($requestUrl)
    {
        // If there is a query part of the request url, remove it
        if(strpos($requestUrl, '?') !== false) {
            $requestUrl = substr($requestUrl,0,strpos($requestUrl, '?'));
        }

        // Made sure the request url is valid with a trailing slash
        $requestUrl = rtrim($requestUrl, '/') . '/';

        return $requestUrl;
    }

    public function CreateFullUrl()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function GetControllerPath($controllerName, $requestData)
    {
        $usedCore = $this;
        $coreControllerPath = $this->CanHandleRoute($controllerName, $requestData);

        if($coreControllerPath !== false){
            return array(
                'path' => $coreControllerPath,
                'core' => $usedCore
            );
        }else{
            foreach($this->Plugins as $plugin){
                $usedCore = $plugin;
                $pluginControllerPath = $plugin->CanHandleRoute($controllerName, $requestData);
                if($pluginControllerPath !== false){
                    return array(
                        'path' => $pluginControllerPath,
                        'core' => $usedCore
                    );
                }
            }
        }

        return false;
    }

    public function CanHandleRoute($controllerName)
    {
        $controllerClassName = $controllerName . 'Controller';
        $controllerPath = Directory($this->GetControllerFolder() . $controllerClassName . '.php');

        // Make sure the required controllers source file exists
        if(file_exists($controllerPath)){
            return $controllerPath;
        }else{
            return false;
        }
    }

    public function GetDeclaredMethods($className) {
        $reflector = new ReflectionClass($className);
        $methodNames = array();
        foreach ($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->class === $className) {
                $methodNames[] = $method->name;
            }
        }
        return $methodNames;
    }

    public function CreateHandler($controllerName, $actionName, $requestData)
    {
        if($this->CapitalizeControllerName()){
            $controllerName = ucfirst($controllerName);
        }

        $controllerClassName = $controllerName . 'Controller';
        $controllerPath = $this->GetControllerPath($controllerName, $requestData);

        // Make sure the required controllers source file exists
        if ($controllerPath === false){
            return array(
                'error' => 1,
                'message' => 'Controller path ' . $controllerClassName . ' not found'
            );
        }

        require_once($controllerPath['path']);

        // Instanciate the controller
        if(!class_exists($controllerClassName)) {
            return array(
                'error' => 1,
                'message' => 'Controller class ' . $controllerClassName . ' dont exists'
            );
        }

        $controller = new $controllerClassName;

        if($this->CapitalizeActionName()){
            $actionName = ucfirst($actionName);
        }

        if(!method_exists($controller, $actionName)){
            return array(
                'error' => 1,
                'message' => 'Called action ' . $actionName . ' does not exists'
            );
        }

        $controller->Core           = $controllerPath['core'];
        $controller->CurrentCore    = $controllerPath['core'];
        $controller->Config         = $controllerPath['core']->GetApplicationConfig();
        $controller->Action         = $actionName;
        $controller->Controller     = $controllerName;
        $controller->Layout         = $this->ApplicationConfig['Application']['DefaultLayout'];
        $controller->Models         = $this->Models;
        $controller->RequestUri     = $this->RequestUrl;
        $controller->FullUri        = $this->FullUrl;
        $controller->RequestString  = $this->RequestString;
        $controller->Parameters     = $requestData['Variables'];
        $controller->Helpers        = $this->Helpers;
        $controller->Logging        = $this->Logging;
        $controller->Caching        = $this->Caching;
        $controller->Helpers->SetCurrentController($controller);

        return array(
            'error' => 0,
            'controller' => $controller,
            'actionName' => $actionName
        );
    }

    public function CreateNotFoundHandler($requestData)
    {
        if(!isset($this->ApplicationConfig['Application']['NotFoundController'])){
            return array(
                'error' => 1,
                'message' => 'Application config missing NotFoundController'
            );
        }
        $notFoundControllerName = $this->ApplicationConfig['Application']['NotFoundController'];

        if(!isset($this->ApplicationConfig['Application']['NotFoundAction'])){
            return array(
                'error' => 1,
                'message' => 'Application config missing NotFoundAction'
            );
        }
        $notFoundAction = $this->ApplicationConfig['Application']['NotFoundAction'];

        return $this->CreateHandler($notFoundControllerName, $notFoundAction, $requestData);
    }

    function ParseData($controller)
    {
        // Find the request method
        $controller->Verb = $_SERVER['REQUEST_METHOD'];

        // Parse the post data sent it
        if(isset($_POST)){
            foreach($_POST as $key => $value) {
                // Special case for the keyword 'data' as the automatic generated input tags uses that prefix
                if($key == 'data' && is_array($value)){
                    foreach($_POST['data'] as $subKey => $subValue){
                        $controller->Post->Add($subKey, $subValue);
                        $controller->Data->Add($subKey, $subValue);
                    }
                }else {
                    $controller->Post->Add($key, $value);
                    $controller->Data->Add($key, $value);
                }
            }
        }

        if(isset($_GET)){
            foreach($_GET as $key => $value) {
                $getData[$key] = $value;
                // Special case for the keyword 'data' as the automatic generated input tags uses that prefix
                if ($key == 'data' && is_array($value)) {
                    foreach ($value as $subKey => $subValue) {
                        $controller->Get->Add($subKey, $subValue);
                        $controller->Data->Add($subKey, $subValue);
                    }
                } else {
                    $controller->Get->Add($key, $value);
                    $controller->Data->Add($key, $value);
                }
            }
        }

        if(isset($_FILES)){
            foreach($_FILES as $key => $file) {
                if(is_array($file['name'])){

                    $files = new DataHelper();
                    for($i = 0; $i < count($file['name']); $i++){
                        $storedFile = new File();
                        if($file['error'][$i] == 0) {
                            $storedFile->Name = $file['name'][$i];
                            $storedFile->Size = $file['size'][$i];
                            $storedFile->Path = $file['tmp_name'][$i];
                            $storedFile->Type = $file['type'][$i];
                            $files[] = $storedFile;
                        }
                    }

                    $controller->Files[$key] = $files;
                }else{
                    if($file['error'] == 0) {
                        $storedFile = new File();
                        $storedFile->Name = $file['name'];
                        $storedFile->Size = $file['size'];
                        $storedFile->Path = $file['tmp_name'];
                        $storedFile->Type = $file['type'];

                        $controller->Files[$key] = $storedFile;
                    }
                }
            }
        }
    }

    public function CreatePlugins()
    {
        $this->Plugins = array();
        $pluginFolder = Directory(PLUGINS_FOLDER);
        if(!is_dir($pluginFolder)){
            mkdir($pluginFolder, 0777, true);
        }
        foreach(GetAllDirectories($pluginFolder) as $plugin){
            $pluginCore = new Core(PLUGINS_FOLDER . $plugin, $this);
            $this->Plugins[] = $pluginCore;
        }
    }

    public function SetupPlugins($capabilities)
    {
        foreach($this->Plugins as $plugin){
            $plugin->SetupCore($plugin->PluginPath, $this, $capabilities);
        }
    }


    public function MigrateDatabase($action)
    {
        require_once ('./ShellLib/DatabaseMigration/IDatabaseMigratorTask.php');
        require_once ('./ShellLib/DatabaseMigration/DatabaseColumn.php');
        require_once ('./ShellLib/DatabaseMigration/DatabaseTableBuilder.php');
        require_once ('./ShellLib/DatabaseMigration/DatabaseTableAlter.php');
        require_once ('./ShellLib/DatabaseMigration/DatabaseDropTable.php');
        require_once ('./ShellLib/DatabaseMigration/DatabaseRunSql.php');
        require_once ('./ShellLib/DatabaseMigration/IDatabaseMigration.php');
        require_once ('./ShellLib/DatabaseMigration/DatabaseMigrator.php');

        $migration =  new DatabaseMigrator($this->Models, $this->Database, $this);
        $migration->MigrationSetup();

        if($action == MIGRATION_UP){
            $migration->Up();
        }else if($action == MIGRATION_DOWN){
            $migration->Down();
        }else if($action == MIGRATION_SEED){
            $migration->Seed();
        }
    }
}