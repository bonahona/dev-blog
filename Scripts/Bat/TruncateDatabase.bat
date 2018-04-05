echo off
REM Calls the TruncateDatabase php


php %~dp0../Common/TruncateDatabase.php %1

echo on