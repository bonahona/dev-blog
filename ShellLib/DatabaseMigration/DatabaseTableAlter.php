<?php
class DatabaseTableAlter implements IDatabaseMigratorTask
{
    /** @var  string TableName */
    public $TableName;

    /** @var DatabaseColumn[] $AddColumns */
    public $AddColumns;

    /** @var String[] $DropColumns */
    public $DropColumns;

    public function __construct($tableName)
    {
        $this->TableName = $tableName;
        $this->AddColumns = array();
        $this->DropColumns = array();
        $this->AlterColumns = array();
    }

    /* @return DatabaseTableAlter */
    public function AddColumn($name, $type, $special = array(), $references = array())
    {
        $this->AddColumns[] = new DatabaseColumn($name, $type, $special, $references);
        return $this;
    }

    /* @return DatabaseTableAlter */
    public function AddReference($table, $column, $special = array(),  $name = '')
    {
        if($name == ''){
            $name = $table . 'Id';
        }

        $this->AddColumn($name, 'int', $special, array('table' => $table, 'column' => $column));
        return $this;
    }

    /* @return DatabaseTableAlter */
    public function DropColumn($name)
    {
        $this->AddColumns[] = $name;
        return $this;
    }

    public function Execute($migrator)
    {
        return $migrator->Database->AlterTable($this, true);
    }

    public function Description()
    {
        return "Altering table " . $this->TableName;
    }
}