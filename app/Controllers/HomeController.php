<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Views;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController 
{
    public function __construct(
        public Views $views
    ) {}

    public function __invoke(Response $response): Response 
    {
        return $this->views->render($response, "home.php"); 
    }
}