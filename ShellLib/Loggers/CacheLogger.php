<?php
// The cache logger does not write its log to any specific place but only stores it in memory until the end of execution.
// The fetch method can e used to get an array of all the log entries that has been entered
// This logger is used to avoid the usage of var_dumps thatputs content directly ito the file stream. Instead, create a CacheLogger and write all var_dumps to it. Later, get them with Fetch();
class CacheLogger implements  ILog
{
    protected $LogEntries;

    public function Setup($config)
    {
        $this->LogEntries = array();
    }

    public function Write($data, $logLevel = LOGGING_NOTICE)
    {
        $this->LogEntries[] = array(
            'Data' => $data,
            'Level' => $logLevel
        );
    }

    public function Fetch()
    {
        $result = array();

        foreach($this->LogEntries as $entry){
            $result[] = $entry['Data'];
        }
        return $result;
    }
}