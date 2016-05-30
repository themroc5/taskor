<?php

namespace medienpol\taskor\job;

use medienpol\taskor\exception\TaskErrorException;
use medienpol\taskor\server\ServerInterface;
use medienpol\taskor\task\CleanDirTask;
use medienpol\taskor\task\ComposerInstallTask;
use medienpol\taskor\task\GitCheckoutTask;
use medienpol\taskor\task\GitFetchTask;
use medienpol\taskor\task\ListDirTask;
use medienpol\taskor\task\MkDirTask;
use medienpol\taskor\task\MoveTask;
use medienpol\taskor\task\MysqlCommandTask;
use medienpol\taskor\task\MysqlPipeTask;
use medienpol\taskor\task\OverwriteFileTask;
use medienpol\taskor\task\RawTask;
use medienpol\taskor\task\ReadFileTask;
use medienpol\taskor\task\RemoveFileTask;
use medienpol\taskor\task\SymlinkTask;
use medienpol\taskor\task\TaskInterface;
use medienpol\taskor\task\WhoamiTask;
use medienpol\taskor\task\Yii2MigrateUpTask;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\log\Logger;

abstract class TaskJob extends Component
{
    public $verboseConsole = false;
    public $inOutVerbose = false;

    /** @var ServerInterface */
    public $server;

    public function taskShortcuts()
    {
        $data = [
            'cleanDir' => CleanDirTask::className(),
            'composerInstall' => ComposerInstallTask::className(),
            'yii2MigrateUp' => Yii2MigrateUpTask::className(),
            'whoAmI' => WhoamiTask::className(),
            'overwriteFile' => OverwriteFileTask::className(),
            'removeFile' => RemoveFileTask::className(),
            'deleteFile' => RemoveFileTask::className(),
            'raw' => RawTask::className(),
            'gitFetch' => GitFetchTask::className(),
            'gitCheckout' => GitCheckoutTask::className(),
            'listDir' => ListDirTask::className(),
            'mkDir' => MkDirTask::className(),
            'readFile' => ReadFileTask::className(),
            'move' => MoveTask::className(),
            'mysqlCommand' => MysqlCommandTask::className(),
            'mysqlPipe' => MysqlPipeTask::className(),
            'symlink' => SymlinkTask::className(),
        ];

        $result = [];

        foreach ($data as $short => $className) {
            $result[mb_strtolower($short)] = $className;
        }

        return $result;
    }

    public function prepareServer(ServerInterface $server)
    {
        if ($this->verboseConsole) {
            Console::stdout('• Preparing server ...');
        }

        try {
            $server->prepare();

            if ($this->verboseConsole) {
                Console::output(Console::renderColoredString("  %N%gSuccessful!%n"));
            }
        } catch (\Exception $e) {
            if ($this->verboseConsole) {
                Console::output(Console::renderColoredString(" %N%rError!%n"));
                Console::output(Console::renderColoredString("%N%r{$e->getMessage()}%n"));
            }
            throw $e;
        }
    }

    public function setServer(ServerInterface $server)
    {
        $this->server = $server;
        if (!$server->isReady()) {
            $this->prepareServer($server);
        }
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

        return $task;
    }

    public function executeTask($name, $options = [])
    {
        $class = $this->getTaskClassName($name);
        $task = $this->createTask($class, $options);
        $task->setJob($this);

        if ($this->verboseConsole) {
            Console::stdout('• ' . $task->getDescription() . ' ...');
        }

        try {
            $requiredParameters = $task->requiredParameters();
            foreach ($requiredParameters as $name) {
                if (!isset($options[$name])) {
                    throw new TaskErrorException("Task is missing required parameter <$name>");
                }
            }

            $cmd = $task->getCommand();

            if ($this->verboseConsole && $this->inOutVerbose) {
                Console::output(Console::renderColoredString("\n > $cmd"));
            }

            $server = $task->getServer();
            
            $cwd = $task->getCwd();
            $result = $server->execute($cmd, $cwd);
            $output = $result->output;

            if ($this->verboseConsole && $this->inOutVerbose) {
                Console::output(Console::renderColoredString(" < $output"));
            }

            if ($result->isSuccessful) {
                if ($this->verboseConsole) {
                    Console::output(Console::renderColoredString(" %N%gSuccessful!%n"));
                }

                $processedOutput = $task->processOutput($output);

                return $processedOutput;
            }

            if (!$task->continueOnError()) {
//                throw new TaskErrorException($result->errorOutput);
                throw new TaskErrorException($output);
            } else {
                if ($this->verboseConsole) {
                    Console::output(Console::renderColoredString(" %N%rError!%n"));
//                    Console::output(Console::renderColoredString("%N%r$result->errorOutput%n"));
                    Console::output(Console::renderColoredString("%N%r$output%n"));
                }
                return '';
            }

        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($this->verboseConsole) {
                Console::output(Console::renderColoredString(" %N%rError!%n"));
                Console::output(Console::renderColoredString("  %N%r$message%n"));
            }
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
        $this->getLogger()->log($message, Logger::LEVEL_INFO, $category);
    }

    public function warning($message, $category)
    {
        $this->getLogger()->log($message, Logger::LEVEL_WARNING, $category);
    }

    public function error($message, $category)
    {
        $this->getLogger()->log($message, Logger::LEVEL_ERROR, $category);
    }

    public function run()
    {
        try {
            $this->job();
            return true;
        } catch (\Exception $e) {
            if ($this->verboseConsole) {
                Console::output(Console::renderColoredString("%N%r  Error: {$e->getMessage()}%n"));
            }
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

    abstract public function job();
}
