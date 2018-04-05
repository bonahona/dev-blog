<?php

require_once('MigrateDatabaseCore.php');
$migrateDatabaseObject = new MigrateDatabaseCore();
$migrateDatabaseObject->RunTruncateDatabase();
