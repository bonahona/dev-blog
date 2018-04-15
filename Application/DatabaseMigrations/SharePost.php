<?php
class SharePost implements IDatabaseMigration
{
    public function GetUniqueName()
    {
        return 'Y05XBeyMLoBKyTbwZmTD2sRth7ibaJ61';
    }

    public function GetSortOrder()
    {
        return 1;
    }

    public function Up($migrator)
    {
        $migrator->CreateTable('sharepost')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('NavigationLink', 'varchar(256)')
            ->AddColumn('IsValid', 'int(1)', array('not null', 'default 0'))
            ->AddReference('post', 'Id', array('not null'), 'PostId');
    }

    public function Down($migrator)
    {

    }

    public function Seed($migrator)
    {
    }
}