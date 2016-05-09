<?php

namespace medienpol\taskor\task;

use medienpol\taskor\job\BaseTaskJob;
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
//        try {
//            $this->trigger(self::EVENT_BEFORE_TASK);
//            $this->trigger(self::EVENT_TASK_SUCCESSFUL);
//        } catch (\Exception $e) {
//
//            $this->trigger(self::EVENT_TASK_ERROR, $event);
//        } finally {
//            $this->trigger(self::EVENT_AFTER_TASK);
//        }
    }

    public function setTaskJob(BaseTaskJob $job)
    {
        $this->taskJob = $job;
    }

    public function getName()
    {
        return $this->name;
    }

    public function task()
    {
        // noop
    }
}
