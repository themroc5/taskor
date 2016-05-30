<?php

namespace medienpol\taskor\task;

class MoveTask extends BaseTask implements TaskInterface
{
    public $from;
    public $to;

    public function getDescription()
    {
        return 'Moves a file or directory';
    }

    public function getCommand()
    {
        $cmd = "mv {$this->from} {$this->to}";

        return $cmd;
    }

    public function getCwd()
    {
        return null;
    }

    public function requiredParameters()
    {
        return [
            'from',
            'to',
        ];
    }
}
