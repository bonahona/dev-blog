<?php
class OpenGraphDataExtension implements IDatabaseMigration
{
    public function GetUniqueName()
    {
        return 'SmpBWnFSsYLf8Tpxag7PT5ga';
    }

    public function GetSortOrder()
    {
        return 4;
    }

    public function Up($migrator)
    {
        $migrator->AlterTable('post')->AddColumn('OgImageUrl', 'varchar(1024)');
        $migrator->AlterTable('localuserdetails')->AddColumn('FirstName', 'varchar(128)');
        $migrator->AlterTable('localuserdetails')->AddColumn('LastName', 'varchar(128)');
        $migrator->AlterTable('localuserdetails')->AddColumn('FacebookId', 'varchar(256)');

    }

    public function Down($migrator)
    {

    }

    public function Seed($migrator)
    {
    }
}