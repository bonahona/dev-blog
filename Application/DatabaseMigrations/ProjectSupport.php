<?php
class ProjectSupport implements IDatabaseMigration
{
    public function GetUniqueName()
    {
        return '8wgRTB7ykuIP64kqJqTvHZdDHeB5w5V8';
    }

    public function GetSortOrder()
    {
        return 2;
    }

    public function Up($migrator)
    {
        $migrator->CreateTable('project')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('Name', 'varchar(256)')
            ->AddColumn('IsActive', 'int(1)', array('not null', 'default 0'))
            ->AddColumn('IsDeleted', 'int(1)', array('not null', 'default 0'));

        $migrator->AlterTable('post')
            ->AddReference('project', 'Id', array('not null', 'PostId'));
    }

    public function Down($migrator)
    {

    }

    public function Seed($migrator)
    {
    }
}