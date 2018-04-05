<?php
class DatabaseRunSql implements IDatabaseMigratorTask
{
    /** @var  string Sql */
    public $Sql;

    public function __construct($sql)
    {
        $this->Sql = $sql;
    }

    public function Execute($migrator)
    {
        return $migrator->Database->RunSql($this->Sql);
    }

    public function Description()
    {
        return "Running Sql: " . $this->Sql;
    }
}