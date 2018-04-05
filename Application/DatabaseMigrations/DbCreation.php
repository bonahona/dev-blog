<?php
class DbCreation implements IDatabaseMigration
{
    public function GetUniqueName()
    {
        return 'SF7R8FlxEqi9JJ0tSJzEyh5Ir0ZdE8Uk';
    }

    public function GetSortOrder()
    {
        return 0;
    }

    public function Up($migrator)
    {
        $migrator->CreateTable('localuser')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('ShellUserId', 'int');
    }

    public function Down($migrator)
    {

    }

    public function Seed($migrator)
    {

    }
}