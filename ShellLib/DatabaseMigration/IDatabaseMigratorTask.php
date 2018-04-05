<?php
interface IDatabaseMigratorTask
{
    /** @param DatabaseMigrator $migrator */
    function Execute($migrator);
    function Description();
}