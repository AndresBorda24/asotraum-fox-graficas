<?php

namespace App\Services;

use function App\trimUtf8;

/**
 * Esta clase se encarga de dar forma a las consultas realizadas desde los
 * modelos.
 */
class QXFormatterService
{
    public function forCount(\PDOStatement $result): array
    {
        $data = [];
        while($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $lugar = trimUtf8($row["lugar_nombre"]);
            $data[$lugar] ??= [
                "total"         => 0,
                "Cumplidas"     => 0,
                "Canceladas"    => 0,
                "Pendientes"    => 0,
                "Ambulatorias"  => 0,
                "Hospitalarias" => 0
            ];

            match ($row["cumplida"]) {
                "N" => $data[$lugar]["Canceladas"]++,
                "P" => $data[$lugar]["Pendientes"]++,
                "S" => $data[$lugar]["Cumplidas"]++
            };

            match ($row["tipo"]) {
                "A" => $data[$lugar]["Ambulatorias"]++,
                "H" => $data[$lugar]["Hospitalarias"]++,
            };

            $data[$lugar]["total"]++;
        }
        return $data;
    }

    public function forMotivosCancelacion(\PDOStatement $result): array
    {
        $data = [
            "_data"   => [],
            "motivos" => []
        ];

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $lugar = trimUtf8($row["lugar_nombre"]);
            $motiv = trimUtf8($row["motivo_cancelacion"]);
            $motivCod = trimUtf8($row["motivo_cod"]);

            $data["_data"][$lugar] ??= [];
            $data["_data"][$lugar][$motivCod] ??= 0;
            $data["_data"][$lugar][$motivCod] += 1;

            if (! array_key_exists($motivCod, $data["motivos"])) {
                $data["motivos"][$motivCod] = $motiv;
            }
        }
        return $data;
    }
}