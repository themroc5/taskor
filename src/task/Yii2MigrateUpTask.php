<?php

namespace medienpol\taskor\task;

use yii\base\Component;

class Yii2MigrateUpTask extends BaseTask implements TaskInterface
{
    public $path;

    public function getName()
    {
        return 'Migrating';
    }

    public function getDescription()
    {
        return "Migrating the DB to the latest revision";
    }

    public function getCommand()
    {
        $cmd = "./yii migrate/up --interactive=0";

        return $cmd;
    }

    public function getCwd()
    {
        return $this->path;
    }

    public function requiredParameters()
    {
        return [
            'path',
        ];
    }

}
