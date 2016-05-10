<?php

namespace medienpol\taskor\job;

use medienpol\taskor\exception\TaskErrorException;
use medienpol\taskor\server\ServerInterface;
use medienpol\taskor\task\CleanDirTask;
use medienpol\taskor\task\TaskInterface;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\log\Logger;

abstract class BaseTaskJob extends Component
{
    public $verboseConsole = false;

    /** @var ServerInterface */
    public $server;

    public function setServer(ServerInterface $server)
    {
        $this->server = $server;
        $this->server->prepare();
    }

    /**
     * @param $name
     * @param array $options
     * @return TaskInterface
     */
    private function createTask($class, $options = [])
    {
        /** @var TaskInterface $task */
        $task = new $class($options);
        $task->setTaskJob($this);

        return $task;
    }

    public function executeTask($name, $options = [])
    {
        $class = $this->getTaskClassName($name);
        $task = $this->createTask($class, $options);

        if ($this->verboseConsole) {
            Console::stdout($task->getDescription() . '...');
        }

        try {
            $cmd = $task->getCommand();
            $cwd = $task->getCwd();
            $result = $this->server->execute($cmd, $cwd);

            if ($result->isSuccessful) {
                if ($this->verboseConsole) {
                    Console::output(Console::renderColoredString(" %N%gSuccessful!%n"));
                }
                return $result->output;
            }

            throw new TaskErrorException($result->errorOutput);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            Console::output(Console::renderColoredString(" %N%rError!%n"));
            Console::output(Console::renderColoredString(" %N%r$message%n"));
            throw $e;
        }
    }

    /**
     * @return \yii\log\Logger
     */
    public function getLogger()
    {
        return \Yii::getLogger();
    }

    public function info($message, $category)
    {
        $this->taskJob->getLogger()->log($message, Logger::LEVEL_INFO, $category);
    }

    public function warning($message, $category)
    {
        $this->taskJob->getLogger()->log($message, Logger::LEVEL_WARNING, $category);
    }

    public function error($message, $category)
    {
        $this->taskJob->getLogger()->log($message, Logger::LEVEL_ERROR, $category);
    }

    public function taskShortcuts()
    {
        return [
            'cleandir' => CleanDirTask::className(),
        ];
    }

    public function run()
    {
        try {
            $this->job();
            return true;
        } catch (\Exception $e) {
            return false;
        } finally {

        }
    }
    private function getTaskClassName($name)
    {
        $lowerName = mb_strtolower($name);
        $shortcuts = $this->taskShortcuts();
        return ArrayHelper::getValue($shortcuts, $lowerName, $name);
    }


    public function job()
    {
        $className = get_called_class();
        throw new \Exception("$className::job() must be implemented!");
    }


}
