<?php

namespace medienpol\taskor\server;

use Symfony\Component\Process\Process;

class LocalServer implements ServerInterface
{

    /**
     * @return boolean
     */
    public function prepare()
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function isReady()
    {
        return true;
    }

    /**
     * @param string $command
     * @return ServerReply
     * @internal param $timeout
     */
    public function execute($command, $cwd = null)
    {
        $process = new Process($command, $cwd);
        $process->setPty(false);
        $process->run();
        
        $reply = new ServerReply();
        $reply->isSuccessful = $process->isSuccessful();
        $reply->code = $process->getExitCode();
        $reply->output = trim($process->getOutput());
//        $reply->errorOutput = trim($process->getErrorOutput());

        return $reply;
    }
}
