<?php

// Base class for implementation of logging files
interface ILog
{
    public function Setup($config);
    public function Write($data, $logLevel = LOGGING_NOTICE);
}