<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Views;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController 
{
    public function __construct(
        public Views $views
    ) {}

    public function __invoke(Request $request, Response $response): Response 
    {
        $this->views->setRouteContext($request);
        return $this->views->render($response, "home/index.php", [
            "__asset" => "assets/home/index.js",
            "__title" => "Estadísticas - Asotrauma"
        ]); 
    }
}