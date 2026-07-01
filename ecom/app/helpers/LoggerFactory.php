<?php
require_once __DIR__ . '/../../php/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class LoggerFactory
{
    // Store multiple logger channels dynamically
    private static array $loggers = [];

    public static function getLogger(?string $channelName = null): Logger
    {
        // 1. Load configuration
        $config = require __DIR__ . '/../app_config/log_config.php';

        // 2. Fallback to default config name if none is passed
        $channel = $channelName ?? $config['channel_name'];

        // 3. Return existing instance if already configured
        if (isset(self::$loggers[$channel])) {
            return self::$loggers[$channel];
        }

        // 4. Create new dynamic channel instance
        $logger = new Logger($channel);
        $formatter = new LineFormatter($config['format'], "Y-m-d H:i:s");

        foreach ($config['handlers'] as $handlerConfig) {
            $handler = new StreamHandler(
                $handlerConfig['path'],
                $handlerConfig['level']
            );

            $handler->setFormatter($formatter);
            $logger->pushHandler($handler);
        }

        // 5. Cache and return the instance
        self::$loggers[$channel] = $logger;
        return $logger;
    }
}
