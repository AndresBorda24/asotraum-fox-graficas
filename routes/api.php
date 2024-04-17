<?php
declare(strict_types=1);

use Slim\App;
use App\Controllers\Api\AdmisionesController;
use App\Controllers\Api\QXController;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\Api\VentasController;
use App\Middleware\StartEndDatesMiddleware;

/**
 * Carga las Rutas de la `Api`
*/
function loadApiRoutes(App $app): void {
    $app->group("/api", function(RouteCollectorProxy $api) {
        $api->group("/qx", function(RouteCollectorProxy $adm) {
            $adm->get("/summary", [QXController::class, "summary"] );
        });

        $api->group("/admisiones", function(RouteCollectorProxy $adm) {
            $adm->get("/summary", [AdmisionesController::class, "summary"] );
        });
    });

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
        $group->get("/resumen-x-entidad", [
            VentasController::class,
            "resumenPorEntidad"
        ]);
        $group->get("/excel", [
            VentasController::class,
            "excel"
        ]);
    })->add(StartEndDatesMiddleware::class);
}
