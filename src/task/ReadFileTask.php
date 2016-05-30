<?php

namespace medienpol\taskor\task;

class ReadFileTask extends BaseTask implements TaskInterface
{
    public $path;

    public function getDescription()
    {
        return 'Reading the content of a file';
    }

    public function getCommand()
    {
        return "cat $this->path";
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
