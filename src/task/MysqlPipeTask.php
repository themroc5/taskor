<?php

namespace medienpol\taskor\task;

class MysqlPipeTask extends BaseTask implements TaskInterface
{
    public $username;
    public $password;
    public $database;
    public $source;

    public function getDescription()
    {
        return 'Piping something into MySql.';
    }

    public function getCommand()
    {
        $username = $this->username;
        $password = $this->password;
        $database = $this->database;
        $source = $this->source;

        $cmd = "mysql -u$username -p$password";
        if ($database) {
            $cmd .= " $database";
        }

        $cmd .= " < $source";

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
            'source',
        ];
    }
}
