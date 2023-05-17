<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\ConnectionFox;
use Psr\Http\Message\ResponseInterface as Response;

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

    public function test(Response $response): Response
    {
        $data = $this->conn->query("
            SELECT
                tercero,
                nom_terce,
                COUNT(tercero)  AS total,
                SUM(vr_gravado) AS gravado,
                SUM(vr_exento)  AS exento,
                SUM(financ_vr)  AS copago,
                SUM(iva_bienes) AS iva
            FROM GEMA10.D/VENTAS/DATOS/VTFACC18
            WHERE
                BETWEEN(fecha, CTOD('05/01/2018'), CTOD('05/30/2018'))
                AND LIKE('<< ANULADA >>*', observac)
            ORDER BY total DESC
            GROUP BY tercero
        ");

        $formatted = [];

        while($reg = $data->fetch()) {
            array_push($formatted, [
                "tercero"     => $reg->tercero,
                "nom_terce"   => trim(mb_convert_encoding($reg->nom_terce, 'UTF-8')),
                "copago"      => (int) $reg->copago,
                "gravado"     => (int) $reg->gravado,
                "exento"      => (int) $reg->exento,
                "iva"         => (int) $reg->iva,
                "total"       => (
                    (int) $reg->gravado +
                    (int) $reg->exento +
                    (int) $reg->iva
                ) - (int) $reg->copago,
                "facturas_totales" => (int) $reg->total,
            ]);
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
            LIKE('<< ANULADA >>*', observac)
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

