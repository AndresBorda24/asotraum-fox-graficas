<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Views;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class VentasController
{
    public function __construct(
        private Views $views
    ) {}

    public function index(Request $request, Response $response): Response
    {
        $this->views->setRouteContext($request);

        return $this->views->render($response, "ventas/index.php", [
            "__title" => "Estadísticas Ventas - Gráficas",
            "__asset" => "assets/ventas/index.js"
        ]);
    }

    public function grilla(Request $request, Response $response): Response
    {
        $this->views->setRouteContext($request);

        return $this->views->render($response, "ventas/grilla.php", [
            "__title" => "Estadísticas Ventas - Grilla",
            "__asset" => "assets/ventas/grilla.js"
        ]);
    }
}
