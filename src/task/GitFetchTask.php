<?php

namespace medienpol\taskor\task;

class GitFetchTask extends BaseTask implements TaskInterface
{
    public $localBranchName = 'master';
    public $remoteBranchName = 'master';
    public $remoteName = 'origin';
    public $path;

    public function getName()
    {
        return 'Git Fetch';
    }
    
    public function getDescription()
    {
        return 'Fetch data from the repository';
    }

    public function getCommand()
    {
        return "git fetch {$this->remoteName} {$this->remoteBranchName}:{$this->localBranchName}";
    }

    public function getCwd()
    {
        return $this->path;
    }

    public function requiredParameters()
    {
        return [
            'path',
            'localBranchName',
            'remoteBranchName',
            'remoteName',
        ];
    }
}
