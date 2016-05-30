<?php

namespace medienpol\taskor\task;

use yii\base\Component;

class ComposerInstallTask extends BaseTask implements TaskInterface
{
    public $path;
    public $noDev = true;

    public function getName()
    {
        return 'Composer Install';
    }

    public function getDescription()
    {
        return "Installing Composer dependencies";
    }

    public function getCommand()
    {
        $cmd = "composer install --no-interaction";
        if ($this->noDev) {
            $cmd .= ' --no-dev';
        }

        return $cmd;
    }

    public function getCwd()
    {
        return $this->path;
    }

    public function requiredParameters()
    {
        return [
            'path'
        ];
    }
}
