<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class CreateDatabaseLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $logger = new Logger('ub-baser');
        $logger->pushProcessor(new IntrospectionProcessor());
        $logger->pushHandler(new DatabaseLoggingHandler());

        return $logger;
    }
}
