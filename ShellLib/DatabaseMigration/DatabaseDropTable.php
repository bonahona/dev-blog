<?php
class DatabaseDropTable implements IDatabaseMigratorTask
{
    /** @var  string TableName */
    public $TableName;

    public function __construct($tableName)
    {
        $this->TableName = $tableName;
    }

    public function Execute($migrator)
    {
        return $migrator->Database->DropTable($this);
    }

    public function Description()
    {
        return "Dropping table " . $this->TableName;
    }
}