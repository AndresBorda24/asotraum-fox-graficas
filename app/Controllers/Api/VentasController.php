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
        $fmt->setFacturadoSchema($start, $end);

        /* Se realiza la consulta */
        $data = $this->conn->query("
            SELECT
                V.tercero,
                T.nombre,
                COUNT(V.tercero)  AS facturas, (
                    SUM(V.vr_gravado) +
                    SUM(V.vr_exento)  +
                    SUM(V.iva_bienes)
                ) - SUM(V.financ_vr) AS total
            FROM GEMA10.D/VENTAS/DATOS/VTFACC23 V
            LEFT JOIN GEMA10.D/DGEN/DATOS/TERCEROS T
                ON V.tercero = T.codigo
            WHERE
                BETWEEN(fecha, CTOD('$start'), CTOD('$end'))
                AND ! LIKE('<< ANULADA >>*', observac)
            ORDER BY total DESC
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
        $year  = substr($start, 6);

        // Lo usamos para dar formato a la respuesta.
        $fmt = new VtFormatter();
        $fmt->setResumenGeneralSchema($start, $end);

        $query = "
            SELECT COUNT(*)  AS total_records, (
                    SUM(V.vr_gravado) +
                    SUM(V.vr_exento)  +
                    SUM(V.iva_bienes)
                ) - SUM(V.financ_vr) AS total
            FROM GEMA10.D/VENTAS/DATOS/VTFACC$year V
            LEFT JOIN GEMA10.D/DGEN/DATOS/TERCEROS T
                ON V.tercero = T.codigo
            WHERE %s
        ";

        /** --------------------------------------------------------------------
         *  Sin Radicacion
         * ---------------------------------------------------------------------
        */
        $faturado = $this->conn->query(sprintf($query, "
            BETWEEN(fecha, CTOD('$start'), CTOD('$end'))
            AND radicacion = 0
            AND ! LIKE('<< ANULADA >>*', observac)
        "));

        $fmt->resumenGeneral($faturado, "sin-radicacion");

        /** --------------------------------------------------------------------
         *  Liberadas
         * ---------------------------------------------------------------------
        */
        $faturado = $this->conn->query(sprintf($query, "
            BETWEEN(fecha, CTOD('$start'), CTOD('$end'))
            AND radicacion = -2
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

