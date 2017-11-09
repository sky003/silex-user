<?php
/**
 * The service routes mapping.
 *
 * @var \User\Application $app
 */

$app['controllers']
    ->assert('id', '\d+')
    ->method('GET|PUT|DELETE')
    ->convert('id', function ($id) {
        return (int) $id;
    });

$app->get('/', function () {
    return '';
});
