<?php
class PdoSqlLog implements ILog
{
    protected $Database;
    protected $TableName;

    public function Setup($config)
    {
        $this->Database = null;

        if(!isset($config['Name'])){
            trigger_error('Missing MySqlLog name', E_USER_WARNING);
            return;
        }

        $name = $config['Name'];

        // Make sure all connection data exists
        if(!isset($config['Provider'])){
            trigger_error('Missing Provider attribute for PdoSqlLog' . $name, E_USER_WARNING);
            return;
        }

        if(!isset($config['Server'])){
            trigger_error('Missing Server attribute for PdoSqlLog' . $name, E_USER_WARNING);
            return;
        }

        if(!isset($config['Port'])){
            trigger_error('Missing Port attribute for PdoSqlLog' . $name, E_USER_WARNING);
            return;
        }

        if(!isset($config['Username'])){
            trigger_error('Missing Username attribute for PdoSqlLog' . $name, E_USER_WARNING);
            return;
        }

        if(!isset($config['Password'])){
            trigger_error('Missing Password attribute for PdoSqlLog' . $name, E_USER_WARNING);
            return;
        }

        if(!isset($config['Database'])){
            trigger_error('Missing Database attribute for PdoSqlLog' . $name, E_USER_WARNING);
            return;
        }

        if(!isset($config['Table'])){
            trigger_error('Missing Table attribute for PdoSqlLog' . $name, E_USER_WARNING);
            return;
        }

        $provider = $config['Provider'];
        $server = $config['Server'];
        $database = $config['Database'];
        $port = $config['Port'];

        $dataSource = "$provider:dbname=$database;host=$server;port=$port";

        $db = new PDO(
            $dataSource,
            $config['Username'],
            $config['Password']
        );

        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $this->Database = $db;
        $this->TableName = $config['Table'];
    }

    public function Write($data, $logLevel = LOGGING_NOTICE)
    {
        if($this->Database === null || $this->Database === false){
            return;
        }

        $tableName = $this->TableName;
        $sqlStatement = "INSERT INTO $tableName(Data, LogLevel) VALUES(?, ?);";

        if(!$preparedStatement = $this->Database->prepare($sqlStatement)){
            echo "Failed to prepare PDO statement";
            var_dump($this->Database->errorInfo());
        }

        $values = array($data, $logLevel);

        if(!$preparedStatement->execute($values)){
            echo "Failed to execute PDO statement";
            var_dump($this->Database->errorInfo());
        }
    }
}