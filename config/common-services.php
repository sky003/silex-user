<?php
/**
 * Common services registration and configuration.
 *
 * @var \Auth\Application $app
 */

use Silex\Provider\SecurityServiceProvider;

require 'common.php';

$app->register(new SecurityServiceProvider(), [
    'security.encoder.bcrypt.cost' => $app['security.encoder.bcrypt.cost'],
    'security.firewalls' => [],
]);
