<?php
declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface as Response;

if (! function_exists('App\responseJSON')) {
    /**
     * Da formato a la respuesta para devolverla como JSON
    */
    function responseJson(Response $response, mixed $data, int $statusCode = 201): Response
    {
        $payload = json_encode(
            $data,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_THROW_ON_ERROR
        );
        $response->getBody()->write($payload);
        return $response
            ->withHeader("Content-Type", "application/json")
            ->withStatus($statusCode);
    }
}
