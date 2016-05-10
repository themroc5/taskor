<?php

namespace medienpol\taskor\task;

class CleanDirTask extends BaseTask implements TaskInterface
{
    public $keep = [
        '.gitignore',
        '.gitkeep'
    ];

    public $path;

    public function getDescription()
    {
        $path = trim($this->path);
        return "Cleaning directory $path";
    }

    public function getCommand()
    {
        $path = $this->path;
        $cmd = "find $path -type f";

        foreach ($this->keep as $filename) {
            $cmd .= " ! -name '$filename'";
        }

        $cmd .= " -exec rm -f {} +  && find $path -type d -empty -delete";

        return $cmd;
    }
}
