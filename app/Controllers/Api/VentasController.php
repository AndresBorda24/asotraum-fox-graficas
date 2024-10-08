<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Config;
use Slim\Psr7\Stream;
use App\ConnectionFox;
use App\Services\ExcelService;
use Psr\Http\Message\ResponseInterface as Response;
use App\Services\VentasFormatterService as VtFormatter;
use App\Services\VentasResumenPorEntidadService;
use Psr\Http\Message\ServerRequestInterface as Request;

use function App\responseJson;

/**
 * Controlador encargado de los datos para las estadisticas del modulo
 * de ventas.
 */
class VentasController
{
    public function __construct(
        private ConnectionFox $conn,
        private Config $config
    ) {}

    public function facturado(Request $request, Response $response): Response
    {
        // Fechas para consultas de fox (las toma del middleware)
        $start = $request->getAttribute("start");
        $end   = $request->getAttribute("end");
        $year  = substr($start, 6);

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
            FROM GEMA10.D/VENTAS/DATOS/VTFACC$year V
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

    public function resumenGeneral(
        Request $request,
        Response $response
    ):  Response {
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

    public function topFacturadores(
        Request $request,
        Response $response
    ):  Response {
        // Fechas para consultas de fox (las toma del middleware)
        $start = $request->getAttribute("start");
        $end   = $request->getAttribute("end");
        $year  = substr($start, 6);

        $fm = new VtFormatter;
        $fm->setTopFacturadoresSchema($start, $end);

        $data = $this->conn->query("
            SELECT V.quien, M.nombre, (
                    SUM(V.vr_gravado) +
                    SUM(V.vr_exento)  +
                    SUM(V.iva_bienes)
                ) - SUM(V.financ_vr) AS total, count(*)  AS totalConteo
            FROM GEMA10.D/VENTAS/DATOS/VTFACC$year V
            LEFT JOIN GEMA10.D/DGEN/DATOS/MAOPERA2 M
                ON V.quien = M.id
            WHERE
                BETWEEN(fecha, CTOD('$start'), CTOD('$end'))
                AND ! LIKE('<< ANULADA >>*', observac)
            ORDER BY total DESC
            GROUP BY quien
        ");

        $fm->topFacturadores($data);

        return responseJson($response, $fm->getData());
    }

    public function grilla(Request $request, Response $response): Response
    {
        // Fechas para consultas de fox (las toma del middleware)
        $start = $request->getAttribute("start");
        $end   = $request->getAttribute("end");
        $year  = substr($start, 6);

        $fm = new VtFormatter;
        $fm->setGrillaSchema($start, $end);

        $data = $this->conn->query("
            SELECT
                V.fecha, V.observac, V.fech_rad, V.radicacion,
                V.tercero, T.nombre AS nombre_t,
                M.nombre AS nombre_q, (
                    V.vr_gravado +
                    V.vr_exento  +
                    V.iva_bienes
                ) - V.financ_vr AS total
            FROM GEMA10.D/VENTAS/DATOS/VTFACC$year V
            LEFT JOIN GEMA10.D/DGEN/DATOS/MAOPERA2 M
                ON V.quien = M.id
            LEFT JOIN GEMA10.D/DGEN/DATOS/TERCEROS T
                ON V.tercero = T.codigo
            WHERE
                BETWEEN(V.fecha, CTOD('$start'), CTOD('$end'))
            ORDER BY V.fecha
        ");

        $fm->grilla($data);

        return responseJson($response, $fm->getData());
    }

    public function resumenPorEntidad(Request $request, Response $response)
    {
        // Fechas para consultas de fox (las toma del middleware)
        $start = $request->getAttribute("start");
        $end   = $request->getAttribute("end");

        $service = new VentasResumenPorEntidadService(
            $this->conn,
            $start,
            $end
        );

        return responseJson($response, $service->getData());
    }

    /**
     * Esta funcion es la encargada de generar el archivo de excel. El archivo
     * se almacena en una carpeta temporal y luego de enviarlo se elimina.
    */
    public function excel(Request $request, Response $response): Response
    {
        // Fechas para consultas de fox (las toma del middleware)
        $start = $request->getAttribute("start");
        $end   = $request->getAttribute("end");

        $name = "ventas-" . time() . ".xlsx";
        $path = $this->config->get("temp") . "/" . $name;

        $_ = new ExcelService($this->conn);
        $ex = $_->ventas($start, $end, $path);

        // Si no se genera el Excel retornamos false
        if (! $ex) {
            return responseJson($response, false);
        }

        $fh = fopen($path, 'rb');
        $file_stream = new Stream($fh);

        $response = $response
            ->withBody($file_stream)
            ->withHeader(
                'Content-Disposition',
                'attachment; filename=' . $name . ';'
            )->withHeader(
                'Content-Type',
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            )->withHeader(
                'Cache-Control',
                'no-store, no-cache, must-revalidate, max-age=0'
            )->withHeader('Cache-Control', 'post-check=0, pre-check=0')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Content-Length', filesize($path));

        // Eliminamoe el excel del disco
        unlink($path);
        return $response;
    }
}
