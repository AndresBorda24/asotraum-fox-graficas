<?php
declare(strict_types=1);

use DI\Container;
use \DI\Bridge\Slim\Bridge;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;

require __DIR__ . "/../vendor/autoload.php";

$bindings  = require __DIR__ . "/../config/ContainerBindings.php";
$container = new Container($bindings);

$app = Bridge::create($container);

$app->get("/", function(Response $response, PhpRenderer $views): Response {
    return $views->render($response, "index.php");
});

$app->run();
