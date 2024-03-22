<?php
declare(strict_types=1);

namespace App\Models;

use App\ConnectionFox;

use function App\trimUtf8;

class QX
{
    public function __construct(
        public readonly ConnectionFox $db
    ) {}

    public function count(string $date): array
    {
        $f = date("m.d.y", strtotime($date));

        $query = $this->db->query(
            "SELECT
                CR.lugar,
                CR.tipo_ciru AS tipo,
                CR.cumplida,
                PA.nombre as lugar_nombre
            FROM GEMA10.D/SALUD/DATOS/CIRUGPROG  AS CR
            LEFT JOIN GEMA10.D/IPT/DATOS/PUNTO_AT AS PA
                ON CR.lugar = PA.punto_at
            WHERE
                CR.fecha = CTOD('$f')"
        );

        if ($query === false)
            throw new \PDOException("Error en consulta: ". $this->db->errorCode());

        $data = [];
        while($row = $query->fetch(\PDO::FETCH_ASSOC)) {
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
}