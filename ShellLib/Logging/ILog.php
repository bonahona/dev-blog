<?php

// Base class for implementation of logging files
// Implemented by the same log levels and methods as stated by PHP-FIG psr3 but with PascalCase convention used
interface ILog
{
    public function Setup($config, $logging);

    public function Emergency($message, $context = array());
    public function Alert($message, $context = array());
    public function Critical($message, $context = array());
    public function Error($message, $context = array());
    public function Warning($message, $context = array());
    public function Notice($message, $context = array());
    public function Info($message, $context = array());
    public function Debug($message, $context = array());

    public function Log($data, $context = array(), $logLevel = LOGGING_INFO);
}