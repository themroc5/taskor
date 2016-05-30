<?php

namespace medienpol\taskor\task;

class MkDirTask extends BaseTask implements TaskInterface
{
    public $path;
    public $chmod = null;

    public function getDescription()
    {
        return 'Creates a directory';
    }

    public function getCommand()
    {
        $cmd = "mkdir $this->path";
        if ($this->chmod) {
            $cmd .= " && chmod {$this->chmod} {$this->path}";
        }

        return $cmd;
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
