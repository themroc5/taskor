<?php

namespace medienpol\taskor\task;

use yii\base\Component;

class CleanDirTask extends BaseTask implements TaskInterface
{
    public $keep = [
        '.gitignore',
        '.gitkeep'
    ];

    public $path;

    public function getName()
    {
        return 'Clean Directory';
    }

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

        $cmd .= " -exec rm -f {} + && find $path -type d -empty -delete";

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

    public function continueOnError()
    {
        return true;
    }
}
