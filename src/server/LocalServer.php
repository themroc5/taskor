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
     * @param string $cmd
     * @param array $env
     * @param string $input
     * @param $timeout
     * @return ServerReply
     */
    public function execute($cmd, $cwd = null)
    {
        $process = new Process($cmd, $cwd);
        $process->setPty(true);
        $process->run();
        
        $reply = new ServerReply();
        $reply->isSuccessful = $process->isSuccessful();
        $reply->code = $process->getExitCode();
        $reply->output = $process->getOutput();
        $reply->errorOutput = $process->getErrorOutput();

        return $reply;
    }
}
