<?php

namespace medienpol\taskor\task;

use medienpol\taskor\exception\TaskErrorException;

class ListDirTask extends BaseTask implements TaskInterface
{
    public $path;
    public $onlyDirectories = false;

    public function getDescription()
    {
        return 'Directory List';
    }

    public function getCommand()
    {
        return 'ls -aF1Q';
    }

    public function getCwd()
    {
        return $this->path;
    }

    public function requiredParameters()
    {
        return ['path'];
    }

    public function processOutput($output)
    {
        $entries = preg_split("/\\r\\n|\\r|\\n/", $output);
        $result = [];
        foreach ($entries as $entry) {
            if (empty($entry)) {
                continue;
            }

            if (!preg_match('/"(.+)"(.*)/', $entry, $matches)) {
                throw new TaskErrorException("List result entry has wrong format: $entry");
            }

            list($devnull, $name, $info) = $matches;

            if (in_array($name, ['.', '..'])) {
                continue;
            }

            $isFile = $info == '';
            $isDirectory = $info == '/';

            if ($this->onlyDirectories && !$isDirectory) {
                continue;
            }

            $result[] = [
                'name' => $name,
                'isFile' => $isFile,
                'isDirectory' => $isDirectory,
                'isLink' => $info == '@',
                'isExecutable' => $info == '*',
                'isSocket' => $info == '=',
                'isPipe' => $info == '|',
                'isDoor' => $info == '>',
            ];
        }

        return $result;
    }
}
