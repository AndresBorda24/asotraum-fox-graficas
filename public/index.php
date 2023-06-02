<?php
declare(strict_types=1);

use DI\Container;
use \DI\Bridge\Slim\Bridge;

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../routes/web.php";
require __DIR__ . "/../routes/api.php";

/** Cargamos variables de entorno */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ ."/..");
$dotenv->load();

/**
* Creacion del contenedor
*/
$bindings  = require __DIR__ . "/../config/ContainerBindings.php";
$container = new Container($bindings);

$app = Bridge::create($container);

/**
 * Unos Middleware de Slim.
 * El ErrorMiddleware debe siempre ser agregado al ultimo.
 */
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);

$app->setBasePath($_ENV["APP_PATH"]);

/**
 * Rutas de la aplicacion
*/
loadWebRoutes($app);
loadApiRoutes($app);

$app->run();
