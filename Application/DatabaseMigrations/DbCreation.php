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

        $migrator->CreateTable('poststatus')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('DisplayName', 'varchar(128)');

        $migrator->CreateTable('post')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('Title', 'varchar(512)')
            ->AddColumn('NavigationTitle', 'varchar(512)')
            ->AddColumn('CreateDate', 'varchar(64)')
            ->AddColumn('PublishDate', 'varchar(64)')
            ->AddColumn('EditDate', 'varchar(64)')
            ->AddColumn('MastHeadImageUrl', 'varchar(1024)')
            ->AddColumn('HomePageText', 'varchar(4096)')
            ->AddColumn('OgTitle', 'varchar(256)')
            ->AddColumn('OgDescription', 'varchar(256)')
            ->AddReference('localuser', 'Id', array('not null'), 'PublishedById')
            ->AddReference('poststatus', 'Id', array('not null'), 'PostStatusId');

        $migrator->CreateTable('posttag')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('IsDeleted', 'int(1)', array('not null', 'default 0'))
            ->AddReference('tag', 'Id', array('not null'), 'TagId')
            ->AddReference('post', 'Id', array('not null'), 'PostId');

        $migrator->CreateTable('postcontent')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('SortOrder', 'int', array('not null', 'default 0'))
            ->AddColumn('IsActive', 'int(1)', array('not null', 'default 0'))
            ->AddColumn('IsDeleted', 'int(1)', array('not null', 'default 0'))
            ->AddColumn('Content', 'text')
            ->AddReference('post', 'Id', array('not null'), 'PostId');

        $migrator->CreateTable('visit')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('SessionId', 'varchar(256)')
            ->AddColumn('RequestUrl', 'varchar(256)')
            ->AddColumn('TimeStamp', 'varchar(128)');
    }

    public function Down($migrator)
    {

    }

    public function Seed($migrator)
    {
        $migrator->Models->Tag->Create(['DisplayName' => 'Design', 'IsActive' => 1])->Save();
        $migrator->Models->Tag->Create(['DisplayName' => 'Programming', 'IsActive' => 1])->Save();
        $migrator->Models->Tag->Create(['DisplayName' => 'Modeling', 'IsActive' => 1])->Save();
        $migrator->Models->Tag->Create(['DisplayName' => 'Texturing', 'IsActive' => 1])->Save();
        $migrator->Models->Tag->Create(['DisplayName' => 'Skinning', 'IsActive' => 1])->Save();
        $migrator->Models->Tag->Create(['DisplayName' => 'Animation', 'IsActive' => 1])->Save();
        $migrator->Models->Tag->Create(['DisplayName' => 'Gameplay', 'IsActive' => 1])->Save();
        $migrator->Models->Tag->Create(['DisplayName' => 'Editor', 'IsActive' => 1])->Save();
        $migrator->Models->Tag->Create(['DisplayName' => 'Sound', 'IsActive' => 1])->Save();
        $migrator->Models->Tag->Create(['DisplayName' => 'Music', 'IsActive' => 1])->Save();

        $migrator->Models->PostStatus->Create(['DisplayName' => 'Draft'])->Save();
        $migrator->Models->PostStatus->Create(['DisplayName' => 'Published'])->Save();
        $migrator->Models->PostStatus->Create(['DisplayName' => 'Detracted'])->Save();
    }
}