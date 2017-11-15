<?php
/**
 * The service routes mapping.
 *
 * @var \User\Application $app
 */

use User\Provider\AccountControllerProvider;

$app['controllers']
    ->assert('id', '\d+')
    ->method('GET|PUT|DELETE')
    ->convert('id', function ($id) {
        return (int) $id;
    });

$app->get('/', function () {
    return '';
});

$app->mount('/account', new AccountControllerProvider());
