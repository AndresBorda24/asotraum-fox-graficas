<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Views;
use Psr\Http\Message\ResponseInterface as Response;

class VentasController
{
    public function __construct(
        private Views $views
    ) {}

    public function index(Response $response): Response
    {
        return $this->views->render($response, "ventas/index.php");
    }
}
