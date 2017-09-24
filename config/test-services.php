<?php
/**
 * Test services registration and configuration.
 *
 * @var \Auth\Application $app
 */

use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Monolog\Formatter\GelfMessageFormatter;
use Monolog\Handler\GelfHandler;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;

require 'test.php';

$app->register(new MonologServiceProvider(), [
    'monolog.use_error_handler' => false,
]);

$app->register(new DoctrineServiceProvider(), [
    'db.options' => $app['db.postgres_options'],
]);
