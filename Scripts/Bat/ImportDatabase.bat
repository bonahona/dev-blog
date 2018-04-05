echo off
REM Calls the importDatabase php script with the source material as parameter. The source must be a JSON file
REM of corresponding formatting. Preferably one created through the export database script.


php %~dp0../Common/ImportDatabase.php %1

echo on