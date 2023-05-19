<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\ConnectionFox;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function App\trimUtf8;
use function App\responseJson;

/**
 * Controlador encargado de los datos para las estadisticas del modulo
 * de ventas.
*/
class VentasController
{
    public function __construct (
        private ConnectionFox $conn
    ){}

    public function facturado(Request $request, Response $response): Response
    {
        /**
         * Obtenemos las fechas de los parametros de la url
        */
        $query = $request->getQueryParams();

        $start = array_key_exists("start", $query)
            ? date("m.d.y", strtotime($query["start"]))
            : date("m.d.y", strtotime('first day of last month'));

        $end = array_key_exists("end", $query)
            ? date("m.d.y", strtotime($query["end"]))
            : date("m.d.y", strtotime('last day of last month'));

        /* Se realiza la consulta */
        $data = $this->conn->query("
            SELECT
                tercero,
                nom_terce,
                COUNT(tercero)  AS total,
                SUM(vr_gravado) AS gravado,
                SUM(vr_exento)  AS exento,
                SUM(financ_vr)  AS copago,
                SUM(iva_bienes) AS iva
            FROM GEMA10.D/VENTAS/DATOS/VTFACC23
            WHERE
                BETWEEN(fecha, CTOD('$start'), CTOD('$end'))
                AND ! LIKE('<< ANULADA >>*', observac)
            ORDER BY exento DESC
            GROUP BY tercero
        ");

        /**
         * A partir de aqui le damos el formato que queremos a la informacion
        */
        $formatted = [
            "total_facturado" => [],
            "total_facturas"  => [],
            "categories"      => [],
            "meta"            => [
                "dates" => [
                    "start" => $start,
                    "end"   => $end
                ]
            ]
        ];

        while($reg = $data->fetch()) {
            if( count($formatted["total_facturado"]) >= 9 ) {
                $formatted["categories"][9] = "otros";

                if(! isset($formatted["total_facturado"][9])) {
                    $formatted["total_facturado"][9] = 0;
                }

                if(! isset($formatted["total_facturas"][9])) {
                    $formatted["total_facturas"][9] = 0;
                }

                $formatted["total_facturas"][9]  += (int) $reg->total;
                $formatted["total_facturado"][9] += (
                    (int) $reg->gravado +
                    (int) $reg->exento +
                    (int) $reg->iva
                ) - (int) $reg->copago;

                continue;
            }

            array_push($formatted["categories"], trimUtf8($reg->nom_terce));
            array_push($formatted["total_facturas"], (int) $reg->total);
            array_push($formatted["total_facturado"],  (
                (int) $reg->gravado +
                (int) $reg->exento +
                (int) $reg->iva
            ) - (int) $reg->copago);
        }

        return responseJson($response, $formatted);
    }

    public function anuladas(Response $response): Response
    {
        $data = $this->conn->query("
            SELECT
                tercero,
                nom_terce,
                COUNT(tercero)  AS total
            FROM GEMA10.D/VENTAS/DATOS/VTFACC18
            WHERE
                BETWEEN(fecha, CTOD('04/18/2023'), CTOD('05/18/2023'))
                AND LIKE('<< ANULADA >>*', observac)
            ORDER BY total DESC
            GROUP BY tercero
        ");

        $formatted = [];

        while($reg = $data->fetch()) {
            if (! array_key_exists($reg->total, $formatted)) {
                $formatted[$reg->total] = array();
            }

            array_push($formatted[ $reg->total ], [
                $reg->tercero => trimUtf8($reg->nom_terce)
            ]);
        }

        return responseJson($response, $formatted);
    }
}

