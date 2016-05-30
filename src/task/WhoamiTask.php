<?php

namespace medienpol\taskor\task;

class WhoamiTask extends BaseTask implements TaskInterface
{
    public function getDescription()
    {
        return 'Who am I?';
    }

    public function getCommand()
    {
        return 'whoami';
    }

    public function getCwd()
    {
        return null;
    }

    public function requiredParameters()
    {
        return [];
    }
}
