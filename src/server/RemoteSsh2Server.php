<?php

namespace medienpol\taskor\server;

use medienpol\taskor\exception\ServerParameterException;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;
use yii\base\Component;

class RemoteSsh2Server extends Component implements ServerInterface
{
    const AUTH_METHOD_PASSWORD = 'AUTH_METHOD_PASSWORD';
    const AUTH_METHOD_RSA_KEY = 'AUTH_METHOD_RSA_KEY';

    public $host;
    public $port = 22;
    public $username;
    public $password;
    public $rsaKeyPath;

    public $authMethod;

    private $isReady = false;

    /** @var SSH2 */
    public $connection;


    public function init()
    {
        parent::init();
    }

    public function prepare()
    {
        $this->isReady = false;

        if (empty($this->host)) {
            throw new ServerParameterException('RemoteSsh2Server requires a host.');
        }

        $this->connection = new SSH2($this->host);

        if (empty($this->authMethod)) {
            throw new ServerParameterException('RemoteSsh2Server requires a authMethod.');
        }

        if ($this->authMethod == RemoteSsh2Server::AUTH_METHOD_PASSWORD) {
            if (empty($this->password)) {
                throw new ServerParameterException('RemoteSsh2Server requires a password.');
            }

            $key = $this->password;

        } elseif ($this->authMethod == RemoteSsh2Server::AUTH_METHOD_RSA_KEY) {
            if (empty($this->rsaKeyPath)) {
                throw new ServerParameterException('RemoteSsh2Server requires a RSA key path.');
            }

            if (!file_exists($this->rsaKeyPath)) {
                throw new ServerParameterException("The file {$this->rsaKeyPath} does not exist.");
            }

            $key = new RSA();
            if ($this->password) {
                $key->password = $this->password;
            }
            $privateKey = file_get_contents($this->rsaKeyPath);

            if (!$key->loadKey($privateKey)) {
                throw new ServerParameterException('RemoteSsh2Server can\'t load the RSA key.');
            }

        } else {
            throw new ServerParameterException('RemoteSsh2Server requires a valid authMethod.');
        }

        if (!$this->connection->login($this->username, $key)) {
            if ($this->connection->isConnected()) {
                throw new ServerParameterException('RemoteSsh2Server can\'t login - wrong credentials.');
            } else {
                throw new ServerParameterException('RemoteSsh2Server can\'t login - can\'t connect.');
            }
        }

//        $this->connection->enableQuietMode();
        $this->isReady = true;
    }

    /**
     * @return boolean
     */
    public function isReady()
    {
        return $this->isReady;
    }

    /**
     * @param string $command
     * @param array $env
     * @param string $input
     * @param $timeout
     * @return ServerReply
     */
    public function execute($command, $cwd = null)
    {
        if ($cwd) {
            $command = "cd $cwd && $command";
        }

        $output = $this->connection->exec($command);

        $reply = new ServerReply();
        $reply->code = (int)$this->connection->getExitStatus();
        $reply->isSuccessful = $reply->code == 0;
        $reply->output = $output;
//        $reply->errorOutput = $this->connection->getStdError();

        return $reply;
    }
}
