<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Views;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class QXController
{
    public function __construct(
        public Views $views
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $this->views->setRouteContext($request);
        return $this->views->render($response, "QX/index.php", [
            "__asset" => "assets/qx/index.js",
            "__title" => "Estadísticas QX - Asotrauma"
        ]);
    }
}