<?php
/**
 * The service bootstrap file used for test purposes.
 */

use User\Application;
use Doctrine\Common\Annotations\AnnotationRegistry;

// Check if the browser tests running.
if (isset($_SERVER['REQUEST_URI'])) {
    // Start the remote code coverage collection.
    require_once __DIR__.'/../c3.php';
}

require_once __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader('class_exists');

defined('SERVICE_ENV') or define('SERVICE_ENV', Application::ENV_TEST);

$app = new Application();

require __DIR__.'/../config/'.SERVICE_ENV.'-services.php';
require __DIR__.'/../config/common-services.php';

require __DIR__.'/../config/routes.php';

$app->run();
