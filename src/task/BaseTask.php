<?php

namespace medienpol\taskor\task;

use medienpol\taskor\exception\TaskErrorException;
use medienpol\taskor\job\BaseTaskJob;
use medienpol\taskor\server\ServerInterface;
use yii\base\Component;
use yii\base\Event;
use yii\log\Logger;

abstract class BaseTask extends Component
{
    const EVENT_BEFORE_TASK = 'EVENT_BEFORE_TASK';
    const EVENT_AFTER_TASK = 'EVENT_AFTER_TASK';
    const EVENT_TASK_SUCCESSFUL = 'EVENT_TASK_SUCCESSFUL';
    const EVENT_TASK_ERROR = 'EVENT_TASK_ERROR';

    /** @var BaseTaskJob */
    public $taskJob;
    /** @var ServerInterface */
    public $server;

    public $name = '';
    public $logCategory = '';

    public function init()
    {
        if (empty($this->logCategory)) {
            $this->logCategory = get_class($this);
        }

        if (empty($this->name)) {
            $this->name = get_class($this);
        }
    }

    public function execute()
    {
        $this->task();
    }

    public function setTaskJob(BaseTaskJob $job)
    {
        $this->taskJob = $job;
    }

    public function setServer(ServerInterface $server)
    {
        $this->server = $server;
        $this->server->prepare();
    }

    public function getServer()
    {
        if ($this->server) {
            return $this->server;
        }

        if ($this->taskJob->server) {
            return $this->taskJob->server;
        }

        throw new TaskErrorException('Server must be set!');
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCommand()
    {
        // noop
    }

    public function getCwd()
    {
        return null;
    }
}
