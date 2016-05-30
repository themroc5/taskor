<?php

namespace medienpol\taskor\server;

interface ServerInterface
{
    /**
     * @return boolean
     */
    public function prepare();

    /**
     * @return boolean
     */
    public function isReady();

    /**
     * @param string $command
     * @param string $cwd
     * @return ServerReply
     */
    public function execute($command, $cwd);
}
