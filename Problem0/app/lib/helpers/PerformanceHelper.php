<?php

namespace app\lib\helpers;

class PerformanceHelper
{
    /**
     * @return float
     */
    public static function getElapsedTime(): float
    {
        $time = (microtime(true) - START_TIME);

        return number_format($time, 4);
    }
}