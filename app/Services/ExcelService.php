<?php

declare(strict_types=1);

namespace App\Services;

use App\ConnectionFox;
use Shuchkin\SimpleXLSXGen;

use function App\trimUtf8;

class ExcelService
{
    public function __construct(
        private ConnectionFox $db
    ) {
    }

    /**
     * @param string $start Fecha Inicial de la consulta
     * @param string $end   Fecha de corte para la consulta
     * @param string $name Debe ser la ruta absoluta mas el nombre del
     * archivo. Ej: c:/temp/excel.xlsx
     *
     * @return bool `true` en caso de que se guarde con exito, `false` en caso
     * contrario
    */
    public function ventas(string $start, string $end, string $name): bool
    {
        try {
            $year  = substr($start, 6);

            $data = $this->db->query("
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

            $formatted = [
                // Estos son los encabezados del excel
                [
                    "Tercero",
                    "Nom.Tercero",
                    "Quien",
                    "Fecha",
                    "Fecha.Rad",
                    "Radicacion",
                    "Valor Factura",
                    "Observacion",
                ]
            ];

            while ($reg = $data->fetch()) {
                $fech_rad = trimUtf8($reg->fech_rad);

                array_push($formatted, [
                    trimUtf8($reg->tercero),
                    trimUtf8($reg->nombre_t),
                    trimUtf8($reg->nombre_q),
                    trimUtf8($reg->fecha),
                    ($fech_rad === '1899-12-30') ? null : $fech_rad,
                    trimUtf8($reg->radicacion),
                    "$" . number_format((int) $reg->total),
                    trimUtf8($reg->observac)
                ]);
            }

            $xlsx = SimpleXLSXGen::fromArray( $formatted );
            return $xlsx->saveAs($name);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
