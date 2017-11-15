<?php
/**
 * Dev services registration and configuration.
 *
 * @var \User\Application $app
 */

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Hateoas\HateoasBuilder;
use Hateoas\UrlGenerator\SymfonyUrlGenerator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;
use Monolog\Formatter\GelfMessageFormatter;
use Monolog\Handler\GelfHandler;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

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

$app->register(new DoctrineServiceProvider(), [
    'db.options' => $app['db.postgres_options'],
]);
$app->register(new DoctrineOrmServiceProvider(), [
    'orm.proxies_dir' => $app['cache.dir'].'/doctrine/orm/Proxies',
    'orm.proxies_namespace' => 'Proxies',
    'orm.em.options' => [
        'mappings' => [
            [
                'type' => 'annotation',
                'use_simple_annotation_reader' => false,
                'namespace' => 'User\Entity',
                'path' => dirname(__DIR__).'/src/Entity',
            ],
        ],
    ],
]);

$app['serializer'] = function () use ($app) {
    // Enable pretty-print json.
    $jsonSerializationVisitor = new JsonSerializationVisitor(
        new SerializedNameAnnotationStrategy(
            new CamelCaseNamingStrategy()
        )
    );
    $jsonSerializationVisitor->setOptions(JSON_PRETTY_PRINT);

    $serializerBuilder = SerializerBuilder::create()
        ->addDefaultDeserializationVisitors()
        ->setSerializationVisitor('json', $jsonSerializationVisitor)
        ->setDebug(true);

    $serializer = HateoasBuilder::create($serializerBuilder)
        ->setUrlGenerator(
            null,
            new SymfonyUrlGenerator($app['url_generator'])
        )
        ->setDebug(true)
        ->build();

    return $serializer;
};

$app->register(new ValidatorServiceProvider());
