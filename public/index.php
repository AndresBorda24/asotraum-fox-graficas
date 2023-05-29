<?php
declare(strict_types=1);

use App\Views;
use DI\Container;
use \DI\Bridge\Slim\Bridge;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\Api\VentasController;
use App\Middleware\StartEndDatesMiddleware;
use Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . "/../vendor/autoload.php";

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

/**
 * Rutas de la app
*/
$app->get("/", function(Response $response, Views $views): Response {
    return $views->render($response, "index.php");
});

$app->group("/api/ventas", function(RouteCollectorProxy $group) {
    $group->get("/facturado", [VentasController::class, "facturado"])
        ->add(StartEndDatesMiddleware::class);
    $group->get("/anuladas", [VentasController::class, "anuladas"]);
    $group->get("/resumen-general", [VentasController::class, "resumenGeneral"])
        ->add(StartEndDatesMiddleware::class);
    $group->get("/top-facturadores", [VentasController::class, "topFacturadores"])
        ->add(StartEndDatesMiddleware::class);
});

$app->run();
