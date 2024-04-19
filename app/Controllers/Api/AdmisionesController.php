<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\Admisiones;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function App\responseJson;

/**
 * Controlador encargado de los datos para las estadisticas del modulo
 * de ventas.
 */
class AdmisionesController
{
    public function __construct(
        private Admisiones $adm
    ) {}

    public function summary(Response $response, Request $request): Response
    {
        @[
            "date" => $date,
            "days" => $days
        ] = $request->getQueryParams() + [ "days" => 1 ];

        $ctrlDate = new \DateTime($date ?? 'now');
        $interval = \DateInterval::createFromDateString('1 day');

        $data = [ "data" => [], "meta" => [ "fechas" => [] ]];

        foreach (range(1, $days) as $k) {
            $d = $ctrlDate->format("Y-m-d");
            $data["meta"]["fechas"][] = $d;
            $data["data"][$d] = $this->adm->count($d);
            $ctrlDate->sub($interval);
        }

        return responseJson($response, $data);
    }
}
