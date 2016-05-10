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
     * @param string $cmd
     * @param string $cwd
     * @return ServerReply
     */
    public function execute($cmd, $cwd);
}
