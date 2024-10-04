<?php
declare(strict_types=1);

use Slim\App;
use App\Controllers\Api\AdmisionesController;
use App\Controllers\Api\QXController;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\Api\VentasController;
use App\Middleware\DatesHandlerMiddleware;
use App\Middleware\StartEndDatesMiddleware;

/**
 * Carga las Rutas de la `Api`
 */
function loadApiRoutes(App $app): void {
    $app->group("/api", function(RouteCollectorProxy $api) {
        $api->group("/qx", function(RouteCollectorProxy $adm) {
            $adm->get("/summary", [QXController::class, "summary"]);
            $adm->get("/medicos", [QXController::class, "medicos"]);
            $adm->get("/motivos-cancelacion", [QXController::class, "motivosCancelacion"]);
            $adm->get("/ocupacion", [QXController::class, "ocupacion"]);
        });

        $api->group("/admisiones", function(RouteCollectorProxy $adm) {
            // Nueva ruta para obtener datos del campo Clasepro
            $adm->get("/clasepro-horas", [AdmisionesController::class, "getDataPorHora"]);
        });
    })->add(DatesHandlerMiddleware::class);

    $app->group("/api/ventas", function(RouteCollectorProxy $group) {
        $group->get("/grilla", [VentasController::class, "grilla"]);
        $group->get("/facturado", [VentasController::class, "facturado"]);
        $group->get("/resumen-general", [VentasController::class, "resumenGeneral"]);
        $group->get("/top-facturadores", [VentasController::class, "topFacturadores"]);
        $group->get("/resumen-x-entidad", [VentasController::class, "resumenPorEntidad"]);
        $group->get("/excel", [VentasController::class, "excel"]);
    })->add(StartEndDatesMiddleware::class);
}
