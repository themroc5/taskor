<?php

namespace medienpol\taskor\task;

class OverwriteFileTask extends BaseTask implements TaskInterface
{
    public $path;
    public $content;

    public function getDescription()
    {
        return 'Create file and write to file';
    }

    public function getCommand()
    {
        $content = $this->content;
        $path = $this->path;
        $eof = '';
        do {
            $eof .= 'EOF';
        } while (strpos($content, $eof) !== false);

        return "touch $path && truncate $path --size 0 && cat << $eof > $path\n$content\n$eof";
    }

    public function getCwd()
    {
        return null;
    }

    public function requiredParameters()
    {
        return [
            'path',
            'content'
        ];
    }
}
