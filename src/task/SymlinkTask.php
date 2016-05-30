<?php

namespace medienpol\taskor\task;

class SymlinkTask extends BaseTask implements TaskInterface
{
    public $targetPath;
    public $linkPath;

    public function getDescription()
    {
        return 'Creating a symlink';
    }

    public function getCommand()
    {
        return "ln -s $this->targetPath $this->linkPath";
    }

    public function getCwd()
    {
        return null;
    }

    public function requiredParameters()
    {
        return [
            'targetPath',
            'linkPath',
        ];
    }
}
