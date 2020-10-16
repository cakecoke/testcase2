<?php

namespace app\lib\logger;

use app\lib\helpers\PerformanceHelper;

class Logger
{
    private const TYPE_INFO = 'INFO';
    private const TYPE_ERROR = 'ERROR';
    private const TYPE_WARNING = 'WARNING';
    /**
     * @var string
     */
    public $target;
    /**
     * @var bool
     */
    public $quiet = true;

    /**
     * @var bool
     */
    public $printWithTime = true;

    /**
     * @param string $message
     */
    public function info(string $message)
    {
        $this->log(self::TYPE_INFO, $message);
    }

    /**
     * @param string $type
     * @param string $message
     */
    private function log(string $type, string $message)
    {
        $message = ucfirst(rtrim($message));

        if (!$this->quiet) {
            $this->printOutput($message);
        }

        file_put_contents(
            $this->target,
            sprintf(
                '%s %.04f %s %s',
                (new \DateTime())->format(DATE_W3C),
                PerformanceHelper::getElapsedTime(),
                $type,
                $message . "\n"
            ),
            FILE_APPEND
        );
    }

    /**
     * @param string $message
     */
    public function error(string $message)
    {
        $this->log(self::TYPE_ERROR, $message);
    }

    /**
     * @param string $message
     */
    public function warn(string $message)
    {
        $this->log(self::TYPE_WARNING, $message);
    }

    /**
     * @param string $message
     */
    private function printOutput(string $message)
    {
        if ($this->printWithTime) {
            printf(
                "%.04f %s\n",
                PerformanceHelper::getElapsedTime(),
                $message
            );

        } else {
            printf(
                "$message\n"
            );
        }
    }
}