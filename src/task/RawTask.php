<?php

namespace medienpol\taskor\task;

class RawTask extends BaseTask implements TaskInterface
{
    public $command;
    public $cwd;
    public $name;
    public $description;

    public function getName()
    {
        return $this->name;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getCwd()
    {
        if (!empty($this->cwd)) {
            return $this->cwd;
        } else {
            return null;
        }
    }

    public function requiredParameters()
    {
        return [
            'command',
            'name',
            'description'
        ];
    }
}
