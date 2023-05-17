<?php
declare(strict_types=1);

use App\Controllers\Api\VentasController;
use DI\Container;
use Slim\Views\PhpRenderer;
use \DI\Bridge\Slim\Bridge;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . "/../vendor/autoload.php";

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

/**
 * Rutas de la app
*/
$app->get("/", function(Response $response, PhpRenderer $views): Response {
    return $views->render($response, "index.php");
});

$app->group("/api/ventas", function(RouteCollectorProxy $group) {
    $group->get("/test", [VentasController::class, "test"]);
    $group->get("/anuladas", [VentasController::class, "anuladas"]);
});

$app->run();
