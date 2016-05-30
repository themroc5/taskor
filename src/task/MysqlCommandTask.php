<?php

namespace medienpol\taskor\task;

use medienpol\taskor\exception\TaskErrorException;

class MysqlCommandTask extends BaseTask implements TaskInterface
{
    public $username;
    public $password;
    public $command;

    public function getDescription()
    {
        return 'Running a MySql command.';
    }

    public function getCommand()
    {
        $username = $this->username;
        $password = $this->password;
        $command = $this->command;

        if (strpos($command, "'") !== false || strpos($command, '"') !== false) {
            throw new TaskErrorException('Command cannot be escaped yet - no \' or " allowed!');
        }

        $cmd = "mysql -u$username -p$password -e '$command'";

        return $cmd;
    }

    public function getCwd()
    {
        return null;
    }

    public function requiredParameters()
    {
        return [
            'username',
            'password',
            'command',
        ];
    }
}
