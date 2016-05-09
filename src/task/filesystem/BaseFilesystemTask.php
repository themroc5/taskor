<?php

namespace medienpol\taskor\task\filesystem;

use medienpol\taskor\task\BaseTask;
use Symfony\Component\Filesystem\Filesystem;

abstract class BaseFilesystemTask extends BaseTask
{
    /** @var Filesystem */
    protected $filesystem;

    public function init()
    {
        parent::init();
        $this->filesystem = new Filesystem();
    }
}
