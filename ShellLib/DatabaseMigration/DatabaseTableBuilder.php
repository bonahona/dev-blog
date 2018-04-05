<?php
class DatabaseTableBuilder implements IDatabaseMigratorTask
{
    /** @var  string TableName */
    public $TableName;

    /** @var DatabaseColumn[] Columns */
    public $Columns;

    public function __construct($tableName)
    {
        $this->TableName = $tableName;
        $this->Columns = array();
    }

    /* @return DatabaseTableBuilder */
    public function AddColumn($name, $type, $special = array(), $references = array())
    {
        $this->Columns[] = new DatabaseColumn($name, $type, $special, $references);
        return $this;
    }

    /* @return DatabaseTableBuilder */
    public function AddPrimaryKey($name, $type)
    {
        $this->AddColumn($name, $type, array('primary key', 'not null', 'auto_increment'));
        return $this;
    }

    /* @return DatabaseTableBuilder */
    public function AddReference($table, $column, $special = array(),  $name = '')
    {
        if($name == ''){
            $name = $table . 'Id';
        }

        $this->AddColumn($name, 'int', $special, array('table' => $table, 'column' => $column));
        return $this;
    }

    public function Execute($migrator)
    {
        return $migrator->Database->BuildTable($this, true);
    }

    public function Description()
    {
        return "Creating table " . $this->TableName;
    }
}