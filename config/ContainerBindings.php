<?php
declare(strict_types=1);

use App\ConnectionFox;
use Slim\Views\PhpRenderer;

return [
    PhpRenderer::class => fn() => new PhpRenderer(
        __DIR__ . "/../templates"
    ),
    ConnectionFox::class => fn() => new ConnectionFox("w:\\"),
];
