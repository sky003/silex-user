<?php
/**
 * The service bootstrap file.
 */

use Auth\Application;

require_once __DIR__.'/../vendor/autoload.php';

defined('SERVICE_ENV') or define('SERVICE_ENV', getenv('SERVICE_ENV'));

$app = new Application();

require __DIR__.'/../config/'.SERVICE_ENV.'-services.php';
require __DIR__.'/../config/common-services.php';

require __DIR__.'/../config/routes.php';

$app->run();
