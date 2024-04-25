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

    /**
     * Agrupa y cuenta el tipo de cirugias realizadas por medicos y devuelve un
     * array ordenado de mayor a menor (con respecto a numero de cirugias
     * realizadas)
     *
     * @param int $limit Define el limite de medicos a retornar.
     */
    public function forMedicos(\PDOStatement $result , int $limit = 10): array
    {
        $data = [];

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $med = trimUtf8($row["medico_nombre"] ?? '');
            $data[$med] ??= [ "A" => 0, "H" => 0 ];

            $data[$med][ $row["tipo"] ] += 1;
        }

        uasort($data, function ($a, $b) {
            $aTotal = $a["A"] + $a["H"];
            $bTotal = $b["A"] + $b["H"];

            if ($aTotal === $bTotal) return 0;

            return ($aTotal > $bTotal) ? -1 : 1;
        });

        return array_slice($data, 0, $limit, true);
    }

    public function forOcupacion(\PDOStatement $result): array
    {
        $data = [];
        $today = date("Y-m-d");
        $tomorrow = date("Y-m-d", strtotime("tomorrow"));

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $qx = trimUtf8($row["quirofano"]);

            $inicio = $row["inicio"];
            $final  = (bool) trim($row["final"])
                ? trim($row["final"])
                : $row["estimada"];

            $hora1 = strtotime("$today $final");
            $hora2 = strtotime("$today $inicio");
            $diff  = $hora1 - $hora2;

            if($diff < 0) {
                $hora1 = strtotime("$tomorrow $final");
                $diff  = $hora1 - $hora2;
            }

            $data[$qx] ??= 0;
            $data[$qx] += $diff;
        }

        foreach ($data as $key => $val) {
            $data[$key] = ceil(($val / 36) / 24);
        }
        return $data;
    }
}