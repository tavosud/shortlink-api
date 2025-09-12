<?php

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/database.php';
require __DIR__ . '/../src/middleware/authmiddleware.php';
require __DIR__ . '/../src/models/urlvalidator.php';
require __DIR__ . '/../src/models/url.php';
require __DIR__ . '/../src/interface/methods_interface.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$container = $app->getContainer();

require __DIR__ . '/../src/routes/urlroutes.php';

$app->run();