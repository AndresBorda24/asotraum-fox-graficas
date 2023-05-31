<?php
declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\Api\VentasController;
use App\Middleware\StartEndDatesMiddleware;

/**
 * Carga las Rutas de la `Api`
*/
function loadApiRoutes(App $app): void {
    $app->group("/api/ventas", function(RouteCollectorProxy $group) {
        $group->get("/grilla", [
            VentasController::class,
            "grilla"
        ]);
        $group->get("/facturado", [
            VentasController::class,
            "facturado"
        ]);
        $group->get("/resumen-general", [
            VentasController::class,
            "resumenGeneral"
        ]);
        $group->get("/top-facturadores", [
            VentasController::class,
            "topFacturadores"
        ]);
        $group->get("/excel", [
            VentasController::class,
            "excel"
        ]);
    })->add(StartEndDatesMiddleware::class);
}
