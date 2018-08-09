<?php
class LocalUserDetailsMigration implements IDatabaseMigration
{
    public function GetUniqueName()
    {
        return 'AtFEqfN6qdrGTtmjeYLCKDZR';
    }

    public function GetSortOrder()
    {
        return 3;
    }

    public function Up($migrator)
    {
        $migrator->CreateTable('localuserdetails')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('ShellUserId', 'int')
            ->AddColumn('Name', 'varchar(256)')
            ->AddColumn('Fetched', 'varchar(64)');
    }

    public function Down($migrator)
    {

    }

    public function Seed($migrator)
    {
    }
}