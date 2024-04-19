<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\QX;
use Psr\Http\Message\ResponseInterface as Response;

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

    public function summary(
        Response $response,
        \DateTimeInterface $from,
        \DateTimeInterface $to
    ): Response {
        return responseJson( $response, [
            "data"  => $this->qx->count($from, $to),
            "dates" => [
                "from" => $from->format("Y-m-d"),
                "to" => $to->format("Y-m-d")
            ]
        ]);
    }

    public function motivosCancelacion(
        Response $response,
        \DateTimeInterface $from,
        \DateTimeInterface $to
    ): Response {
        return responseJson( $response, [
            "data"  => $this->qx->motivosCancelacion($from, $to),
            "dates" => [
                "from" => $from->format("Y-m-d"),
                "to" => $to->format("Y-m-d")
            ]
        ]);
    }
}