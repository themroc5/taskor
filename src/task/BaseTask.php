<?php

namespace medienpol\taskor\task;

use medienpol\taskor\exception\TaskErrorException;
use medienpol\taskor\job\TaskJob;
use medienpol\taskor\server\ServerInterface;
use yii\base\Component;

abstract class BaseTask extends Component
{
    /** @var TaskJob */
    public $job;

    /** @var ServerInterface */
    public $server;

    public function setJob(TaskJob $job)
    {
        $this->job = $job;
    }

    public function getName()
    {
        return $this->className();
    }

    public function info($message)
    {
        $this->job->info($message, $this->getName());
    }

    public function warning($message)
    {
        $this->job->warning($message, $this->getName());
    }

    public function error($message)
    {
        $this->job->error($message, $this->getName());
    }

    public function continueOnError()
    {
        return false;
    }

    public function processOutput($output)
    {
        return $output;
    }

    /**
     * @return bool
     */
    public function hasServer()
    {
        return !!$this->server;
    }

    public function setServer(ServerInterface $server)
    {
        if (!$server->isReady()) {
            $this->job->prepareServer($server);
        }

        $this->server = $server;
    }

    /**
     * @return ServerInterface
     */
    public function getServer()
    {
        if ($this->hasServer()) {
            return $this->server;
        } else {
            return $this->job->server;
        }
    }
}
