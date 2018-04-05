<?php
class DatabaseSeed implements IDatabaseMigratorTask
{
    /* @var Model $Model */
    public $Model;

    public function __construct($model)
    {
        $this->Model = $model;
    }

    public function Execute($migrator)
    {
        return $this->Model->Save();
    }

    public function Description()
    {
        return "Seeding row";
    }
}