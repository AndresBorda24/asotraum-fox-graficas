<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\ConnectionFox;
use Psr\Http\Message\ResponseInterface as Response;
use App\Services\VentasFormatterService as VtFormatter;
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
        // Fechas para consultas de fox (las toma del middleware)
        $start = $request->getAttribute("start");
        $end   = $request->getAttribute("end");

        // Lo usamos para dar formato a la respuesta.
        $fmt = new VtFormatter();
        $fmt->setfacturadoSchema($start, $end);

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

        $fmt->facturado($data);

        return responseJson($response, $fmt->getData());
    }


    public function resumenGeneral(Request $request, Response $response): Response
    {
        // Fechas para consultas de fox (las toma del middleware)
        $start = $request->getAttribute("start");
        $end   = $request->getAttribute("end");

        // Lo usamos para dar formato a la respuesta.
        $fmt = new VtFormatter();
        $fmt->setResumenGeneralSchema($start, $end);

        $query = "
            SELECT tercero, nom_terce,
                COUNT(tercero)  AS total_records, (
                    SUM(vr_gravado) +
                    SUM(vr_exento)  +
                    SUM(iva_bienes)
                ) - SUM(financ_vr) AS total
            FROM GEMA10.D/VENTAS/DATOS/VTFACC23
            WHERE %s
            ORDER BY total DESC
            GROUP BY tercero
        ";

        /** --------------------------------------------------------------------
         *  Facturadas
         * ---------------------------------------------------------------------
        */
        $faturado = $this->conn->query(sprintf($query, "
            BETWEEN(fecha, CTOD('$start'), CTOD('$end'))
            AND radicacion <= 0
            AND ! LIKE('<< ANULADA >>*', observac)
        "));

        $fmt->resumenGeneral($faturado, "liberado");

        /** --------------------------------------------------------------------
         *  Radicadas
         * ---------------------------------------------------------------------
        */
        $radicado = $this->conn->query(sprintf($query, "
            BETWEEN(fecha, CTOD('$start'), CTOD('$end'))
            AND radicacion > 0
            AND ! EMPTY(fech_rad)
            AND ! LIKE('<< ANULADA >>*', observac)
        "));

        $fmt->resumenGeneral($radicado, "radicado");

        /** --------------------------------------------------------------------
         *  Pendientes por radicar
         * ---------------------------------------------------------------------
        */
        $pendiente = $this->conn->query(sprintf($query, "
            BETWEEN(fecha, CTOD('$start'), CTOD('$end'))
            AND radicacion > 0
            AND EMPTY(fech_rad)
            AND ! LIKE('<< ANULADA >>*', observac)
        "));

        $fmt->resumenGeneral($pendiente, "pendiente");

        return responseJson($response, $fmt->getData());
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

