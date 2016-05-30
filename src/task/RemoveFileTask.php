<?php

namespace medienpol\taskor\task;

class RemoveFileTask extends BaseTask implements TaskInterface
{
    public $path;

    public function getDescription()
    {
        return 'Removes a file';
    }

    public function getCommand()
    {
        return "rm $this->path";
    }

    public function getCwd()
    {
        return null;
    }

    public function requiredParameters()
    {
        return [
            'path',
        ];
    }
}
