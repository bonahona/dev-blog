<?php
class DatabaseMigrator
{
    /* @var Models $Models*/
    public $Models;

    /* @var IDatabaseDriver $Database*/
    public $Database;

    /* @var Core $Core */
    public $Core;

    /* @var IDatabaseMigratorTask[] $QueuedTasks */
    private $QueuedTasks = array();

    public function __construct($models, $database, $core)
    {
        $this->Models = $models;
        $this->Database = $database;
        $this->Core = $core;
    }

    public function MigrationSetup()
    {
        $migrationHistory = new DatabaseTableBuilder('__migrationhistory');
        $migrationHistory->AddPrimaryKey('Id', 'int')
            ->AddColumn('Name', 'varchar(128)')
            ->AddColumn('TimeStamp', 'varchar(64)')
            ->AddColumn('Type', 'varchar(64)');
        $migrationHistory->Execute($this);
    }

    public function CreateTable($tableName)
    {
        $result = new DatabaseTableBuilder($tableName);
        $this->QueuedTasks[] = $result;

        return $result;
    }

    public function AlterTable($tableName)
    {
        $result = new DatabaseTableAlter($tableName);
        $this->QueuedTasks[] = $result;

        return $result;
    }

    public function DropTable($tableName)
    {
        $this->QueuedTasks[] = new DatabaseDropTable($tableName);
    }

    public function AddModel($model)
    {
        $this->QueuedTasks[] = new DatabaseSeed($model);
    }

    public function RunSql($sql){
        $this->QueuedTasks[] = new DatabaseRunSql($sql);
    }

    public function Up()
    {
        foreach($this->FindAllMigrations('up') as $migration){
            $this->RunMigrationUp($migration['migration'], $migration['fileName']);
            $this->Database->NotifyMigrationRun($migration['migration']->GetUniqueName(), 'up');
        }
    }

    public function Down()
    {
        foreach($this->FindAllMigrations() as $migration){
            $this->RunMigrationDown($migration['migration'], $migration['fileName']);
        }
    }

    public function Seed()
    {
        foreach($this->FindAllMigrations('seed') as $migration){
            $this->RunMigrationSeed($migration['migration'], $migration['fileName']);
            $this->Database->NotifyMigrationRun($migration['migration']->GetUniqueName(), 'seed');
        }
    }

    public function FindAllMigrations($type = null)
    {
        $migrationFolder = Directory($this->Core->GetDatabaseMigrationFolder());
        if(!is_dir($migrationFolder)){
            return array();
        }

        $migrationClasses = array();
        foreach(GetAllFiles($migrationFolder) as $file){
            $migrationClasses[] = array(
                'className' => explode('.', $file)[0],
                'fileName' => $migrationFolder . $file
            );
        }

        $result = array();
        foreach($migrationClasses as $class){
            require_once ($class['fileName']);
            $instance = new $class['className']();

            if($type == null || !$this->Database->IsMigrationRun($instance->GetUniqueName(), $type)) {
                $result[] = array(
                    'migration' => new $class['className'](),
                    'fileName'
                );
            }

            usort($result, array('DatabaseMigrator', 'SortMigrations'));
        }

        return $result;
    }

    private static function SortMigrations($a, $b)
    {
        return $a['migration']->GetSortOrder() - $b['migration']->GetSortOrder();
    }

    public function RunMigrationUp($migration, $fileName)
    {
        echo "Running $fileName\n";
        $migration->Up($this);
        $this->RunTasks();
    }

    public function RunMigrationDown($migration, $fileName)
    {
        echo "Running $fileName\n";
        $migration->Down($this);
        $this->RunTasks();
    }

    public function RunMigrationSeed($migration, $fileName)
    {
        echo "Running $fileName\n";
        $migration->Seed($this);
        $this->RunTasks();
    }

    public function RunTasks()
    {
        foreach($this->QueuedTasks as $task){
            echo "\n" . $task->Description() . "... \n";
            $result = $task->Execute($this);

            if($result === true){
                echo "done!\n";
            }else{
                echo "failed!\n";
                echo $result . "\n";
            }
        }
    }
}