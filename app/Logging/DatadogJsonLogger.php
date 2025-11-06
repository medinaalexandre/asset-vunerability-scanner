<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DatadogJsonLogger
{
    public function __invoke(array $config)
    {
        $handler = new StreamHandler('php://stdout');
        $handler->setFormatter(new JsonFormatter());

        $logger = new Logger('datadog');
        $logger->pushHandler($handler);

        $logger->pushProcessor(function ($record) {
            $record['extra']['service'] = config('app.name');
            $record['extra']['env'] = config('app.env');

            return $record;
        });

        return $logger;
    }
}
