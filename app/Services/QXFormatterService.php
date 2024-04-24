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
            "total" => [],
            "otros" => []
        ];

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $total = $row["total"];
            $motiv = trimUtf8($row["motivo_cancelacion"]);

            if (count($data["total"]) < 9) {
                $data["total"][$motiv] = (int) $total;
                continue;
            }

            $data["total"]["otros"] ??= 0;
            $data["total"]["otros"] += $total;

            $data["otros"][$motiv] = (int) $total;
        }
        return $data;
    }

    public function forMedicos(\PDOStatement $result): array
    {
        $data = [];

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $med = trimUtf8($row["medico_nombre"]);
            $data[$med] ??= [ "A" => 0, "H" => 0 ];

            $data[$med][ $row["tipo"] ] += 1;
        }

        return $data;
    }
}