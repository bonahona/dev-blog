<?php
interface IDatabaseMigration
{
    /* @return String Unique name of this migration. Makes aure a migration is not run multiple times */
    function GetUniqueName();

    /* @return int Sort order. Order of migrations to run. Lower values has precedence */
    function GetSortOrder();

    /* @param DatabaseMigrator $database*/
    function Up($database);

    /* @param DatabaseMigrator $migrator*/
    function Down($migrator);

    /* @param DatabaseMigrator $migrator*/
    function Seed($migrator);
}