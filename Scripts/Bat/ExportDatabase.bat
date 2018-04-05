echo off
REM Calls the exportDatabase php script with the current executing directory and [optional] the desired output file
REM If no desired output file is specified, a timestamp is created and used as filename

php %~dp0../Common/ExportDatabase.php %cd% %1

echo on