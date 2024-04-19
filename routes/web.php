<?php
declare(strict_types=1);

use Slim\App;
use App\Controllers\HomeController;
use App\Controllers\VentasController;

/**
 * Carga las rutas web de la Aplicacion
*/
function loadWebRoutes(App $app): void {
    $app->get("/", HomeController::class);

    $app->get("/ventas", [VentasController::class,"index"])
        ->setName("ventas");
    $app->get("/ventas/grilla", [VentasController::class,"grilla"])
        ->setName("ventas.grilla");
}
