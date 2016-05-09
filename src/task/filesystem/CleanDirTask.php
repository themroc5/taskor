<?php

namespace medienpol\taskor\task\filesystem;

use medienpol\taskor\task\TaskInterface;

class CleanDirTask extends BaseFilesystemTask implements TaskInterface
{
    public $keep = [
        '.gitignore',
        '.gitkeep'
    ];

    public $path;

    public function getDescription()
    {
        $path = trim($this->path);
        return "Cleaning directory $path";
    }

    public function task()
    {
        $path = \Yii::getAlias($this->path);
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $path) {
            if ($path->isDir()) {
                $dir = (string)$path;
                if (basename($dir) === '.' || basename($dir) === '..') {
                    continue;
                }
                $this->filesystem->remove($dir);
            } else {
                $file = (string)$path;
                if (in_array(basename($file), $this->keep)) {
                    continue;
                }
                $this->filesystem->remove($file);
            }
        }
    }
}
