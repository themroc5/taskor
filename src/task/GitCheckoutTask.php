<?php

namespace medienpol\taskor\task;

class GitCheckoutTask extends BaseTask implements TaskInterface
{
    public $branchName;
    public $repoPath;
    public $targetPath;

    public $force = true;

    public function getName()
    {
        return 'Git Checkout';
    }
    
    public function getDescription()
    {
        return 'Checkout data from repository to target path';
    }

    public function getCommand()
    {
        $command = "git --work-tree=\"{$this->targetPath}\" checkout {$this->branchName}";
        if ($this->force) {
            $command .= ' -f';
        }

        return $command;
    }

    public function getCwd()
    {
        return $this->repoPath;
    }

    public function requiredParameters()
    {
        return [
            'branchName',
            'repoPath',
            'targetPath',
        ];
    }
}
