<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\QX;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function App\responseJson;

/**
 * Controlador encargado de los datos para las estadisticas del modulo
 * de ventas.
 */
class QXController
{
    public function __construct(
        private QX $qx
    ) {}

    public function summary(Response $response, Request $request): Response
    {
        @[
            "from" => $from,
            "to" => $to
        ] = $request->getQueryParams();

        try {
            $to   = new \DateTime($to ? $to : 'now');
            $from = new \DateTime($from ? $from : '7 days ago');
        } catch (\Exception $e) {
            return responseJson( $response, [
                "status"  => false,
                "message" => "Las fechas suministradas no son validas"
            ], 422);
        }

        return responseJson( $response, [
            "data"  => $this->qx->count($from, $to),
            "dates" => [
                "from" => $from->format("Y-m-d"),
                "to" => $to->format("Y-m-d")
            ]
        ]);
    }
}