<?php

namespace medienpol\taskor\task;

use medienpol\taskor\job\BaseTaskJob;

interface TaskInterface
{
    public function setTaskJob(BaseTaskJob $job);

    public function task();
    public function execute();
    public function getName();
    public function getDescription();

    public function on($name, $handler, $data = null, $append = true);
}
