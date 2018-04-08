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

        $migrator->CreateTable('tag')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('DisplayName', 'varchar(128)')
            ->AddColumn('IsActive', 'int(1)', array('not null', 'default 0'));

        $migrator->CreateTable('post')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('Title', 'varchar(512)')
            ->AddColumn('NavigationTitle', 'varchar(512)')
            ->AddColumn('PublishDate', 'varchar(512)')
            ->AddColumn('MastHeadImageUrl', 'varchar(1024)')
            ->AddColumn('HomePageText', 'varchar(4096)')
            ->AddColumn('Status', 'int')
            ->AddReference('localuser', 'Id', array('not null'), 'PublishedById');

        $migrator->CreateTable('posttag')
            ->AddPrimaryKey('Id', 'int')
            ->AddReference('tag', 'Id', array('not null'), 'TagId')
            ->AddReference('post', 'Id', array('not null'), 'PostId');

        $migrator->CreateTable('postcontent')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('SortOrder', 'int', array('not null', 'default 0'))
            ->AddColumn('IsActive', 'int(1)', array('not null', 'default 0'))
            ->AddColumn('Content', 'text')
            ->AddReference('post', 'Id', array('not null'), 'PostId');
    }

    public function Down($migrator)
    {

    }

    public function Seed($migrator)
    {

    }
}