<?php
declare(strict_types=1);

use Slim\App;
use App\Controllers\VentasController;

/**
 * Carga las rutas web de la Aplicacion
*/
function loadWebRoutes(App $app): void {
    $app->redirect("/", "/ventas");

    $app->get("/ventas", [VentasController::class,"index"]);
    $app->get("/ventas/grilla", [VentasController::class,"grilla"]);
}
