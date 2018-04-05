<?php
require_once('ShellLib/Logging/Log.php');

const LOGGING_FATAL = 0;
const LOGGING_WARNING = 1;
const LOGGING_NOTICE = 2;
const LOGGING_FETCH_ALL = 0;       // Not a valid flag when writing to the log, but allowed when fetching messages based on their level

class Logging
{
    /* @var array*/
    protected  $AvailableLoggers;

    /* @var ILog */
    protected $DefaultLogger;

    /* @var array */
    protected $Loggers;

    function __construct()
    {
        $this->AvailableLoggers = array();
        $this->Loggers = array();
    }

    public function AddAvailableLogger($loggerName, $loggerPath)
    {
        // Remove any trailing '.php' in the name

        $loggerName = str_replace('.php', '', $loggerName);
        if(isset($this->AvailableLoggers[$loggerName])){
            return;
        }

        $this->AvailableLoggers[$loggerName] = $loggerPath;
    }

    public function SetupLoggers($applicationConfig = null)
    {
        if($applicationConfig == null){
            return false;
        }

        // No logging element found in the config
        if(!isset($applicationConfig['Logging'])){
            return true;
        }

        // Logging element found but no loggers object in it
        if(!isset($applicationConfig['Logging']['Loggers'])){
            return true;
        }

        foreach ($applicationConfig['Logging']['Loggers'] as $logger) {
            foreach($logger as $type => $config) {

                if(isset($config['Name'])){
                    $name = $config['Name'];
                }

                $createdLoggerResult = $this->AddLogger($name, $type, $config);

                if($createdLoggerResult['Error'] !== 0){
                    trigger_error($createdLoggerResult['Message'], E_USER_ERROR);
                }

                $createdLogger = $createdLoggerResult['Logger'];
                $this->Loggers[$name] = $createdLogger;

                // The first logger to be created will act as the default logger
                if($this->DefaultLogger == null){
                    $this->DefaultLogger = $createdLogger;
                }
            }
        }

        // Everything went well
        return true;
    }

    // Creates a logger and returns it
    protected function AddLogger($name, $type, $config)
    {

        if(isset($this->Loggers[$name])){
            return array(
                'Error' => 1,
                'Message' => 'Logger with name ' . $name . ' does already exists'
            );
        }

        require_once($this->AvailableLoggers[$type]);

        if(!class_exists($type)){
            return array(
                'Error' => 1,
                'Message' => 'Logger class ' . $type . ' does not exist'
            );
        }

        $logger = new $type;
        $logger->Setup($config);

        return array(
            'Error' => 0,
            'Logger' => $logger
        );
    }

    /* @return ILog */
    public function __get($loggerName)
    {
        if(array_key_exists($loggerName, $this->Loggers)){
            return $this->Loggers[$loggerName];
        }else{
            trigger_error('Logger with name ' . $loggerName . ' is not setup in the application config', E_USER_ERROR);
            return null;
        }
    }

    // Wrapper for using the default logger
    public function Write($data, $loggingLevel = LOGGING_NOTICE)
    {
        if($this->DefaultLogger == null){
            trigger_error('No logger is setup is the application config', E_USER_ERROR);
        }

        $this->DefaultLogger->Write($data, $loggingLevel);
    }
}