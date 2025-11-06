<?php

namespace App\Helpers;

use ChaseConey\LaravelDatadogHelper\Datadog;
use Illuminate\Support\Facades\Log;
use Throwable;

class DatadogCollectMetricHelper
{
    public static function registerTimeSpent(string $metricName, $startTime): void
    {
        try {
            Datadog::microtiming($metricName, microtime(true) - $startTime, 1);
        } catch (Throwable $t) {
            $trace = $t->getTrace();
            Log::error("Failed to collect metric $metricName", [
                'message' => $t->getMessage(),
                'code' => $t->getCode(),
                'trace' => array_splice($trace, 0,10),
            ]);
        }
    }
}
