<?php
$action = $argv[1];

require_once('MigrateDatabaseCore.php');
$migrateDatabaseObject = new MigrateDatabaseCore();
$migrateDatabaseObject->RunMigrateDatabase($action);


return 0;