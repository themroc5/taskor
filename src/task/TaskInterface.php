<?php

namespace medienpol\taskor\task;

use medienpol\taskor\job\TaskJob;
use medienpol\taskor\server\ServerInterface;

interface TaskInterface
{
    public function getName();
    public function getDescription();
    public function getCommand();
    public function getCwd();
    
    public function processOutput($output);

    public function requiredParameters();
    public function continueOnError();

    public function setJob(TaskJob $job);
    public function info($message);
    public function warning($message);
    public function error($message);

    public function hasServer();
    public function setServer(ServerInterface $server);

    /**
     * @return ServerInterface
     */
    public function getServer();
}
