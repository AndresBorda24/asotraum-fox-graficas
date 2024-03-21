<?php
declare(strict_types=1);

namespace App\Models;

use App\ConnectionFox;

class Admisiones
{
    public function __construct(
        public readonly ConnectionFox $db
    ) {}

    /**
     * Cuenta el total de admisiones por horas dependiendo de del dia.
     * @param string $fecha Dia en que se contaran las admisiones
     */
    public function count(string $fecha): array
    {
        $f = date("m.d.y", strtotime($fecha));

        $query = $this->db->query(
            "SELECT LEFT(hora, 2) as hora, COUNT(*) as total
            FROM Z:\GEMA10.D\IPT\DATOS\PTOTC00
            WHERE fecha = CTOD(\"$f\")
            GROUP BY 1"
        );

        if ($query === false)
            throw new \PDOException("Error en consulta: ". $this->db->errorCode());

        $admCount = $query->fetchAll(\PDO::FETCH_ASSOC);
        $admCount = array_column($admCount, "total", "hora");

        /* Horas desde 00 hasta 23 */
        $hours = array_map(
            fn($hour) => str_pad((string) $hour, 2, '0', \STR_PAD_LEFT),
            range(0, 23)
        );

        return array_reduce($hours, function(array $carry, string $h) use($admCount) {
            $carry["$h:00"] = intval(@$admCount[$h] ?? 0);
            return $carry;
        }, []);
    }
}