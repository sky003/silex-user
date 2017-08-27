<?php
/**
 * Dev services registration and configuration.
 *
 * @var \Auth\Application $app
 */

use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Monolog\Formatter\GelfMessageFormatter;
use Monolog\Handler\GelfHandler;
use Silex\Provider\MonologServiceProvider;

require 'dev.php';

$app->register(new MonologServiceProvider(), [
    'monolog.use_error_handler' => false,
]);
$app->extend('monolog', function (Monolog\Logger $logger) use ($app) {
    // TODO: Wrap this into service provider.
    $handler = new GelfHandler(
        new Publisher(
            new UdpTransport(
                $app['graylog.options']['host'],
                $app['graylog.options']['port'],
                UdpTransport::CHUNK_SIZE_LAN
            )
        )
    );
    $handler->setFormatter(new GelfMessageFormatter());

    $logger->pushHandler($handler);

    return $logger;
});
