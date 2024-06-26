<?php
declare(strict_types=1);

namespace App\Models;

use App\ConnectionFox;
use App\Services\QXFormatterService;

class QX
{
    public function __construct(
        public readonly ConnectionFox $db,
        public readonly QXFormatterService $formatter
    ) {}

    /** Cuenta las cirugias de quirofano agrupandolas por su tipo y estado */
    public function count(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        $query = $this->db->query(sprintf(
            "SELECT
                CR.lugar,
                CR.tipo_ciru AS tipo,
                CR.cumplida,
                PA.nombre as lugar_nombre
            FROM GEMA10.D/SALUD/DATOS/CIRUGPROG  AS CR
            LEFT JOIN GEMA10.D/IPT/DATOS/PUNTO_AT AS PA
                ON CR.lugar = PA.punto_at
            WHERE
                CR.fecha BETWEEN CTOD('%s') AND CTOD('%s')
                AND CR.moti_canc = '  '",
            $from->format('m.d.y'), $to->format('m.d.y')
        ));

        if ($query === false)
            throw new \PDOException("Error en consulta: ". $this->db->errorCode());

        return $this->formatter->forCount($query);
    }

    /**
     * Obtiene los motivos de cancelacion de las citas en un rando especifico de
     * fechas.
     */
    public function motivosCancelacion(
        \DateTimeInterface $from,
        \DateTimeInterface $to
    ): array {
        $query = $this->db->query(sprintf(
            "SELECT
                COUNT(MC.nombre) AS total,
                MC.nombre AS motivo_cancelacion
            From GEMA10.D/SALUD/DATOS/CIRUGPROG  As CR
            LEFT JOIN gema10.d\SALUD\DATOS\MOTIV_CANC AS MC
                ON CR.moti_canc = MC.codigo
            WHERE
                CR.fecha BETWEEN CTOD('%s') AND CTOD('%s')
                AND CR.moti_canc != '  '
            GROUP BY MC.nombre
            ORDER BY total DESC",
            $from->format('m.d.y'), $to->format('m.d.y')
        ));

        if ($query === false)
            throw new \PDOException("Error en consulta: ". $this->db->errorCode());

        return $this->formatter->forMotivosCancelacion($query);
    }

    /**
     * Obtiene y agrupa los medicos que han realizado cirugias que no han sido
     * canceladas.
     */
    public function medicos(
        \DateTimeInterface $from,
        \DateTimeInterface $to
    ): array {
        $query = $this->db->query(sprintf(
            "SELECT
                CR.tipo_ciru as tipo,
                ME.nombre as medico_nombre
            FROM GEMA10.D/SALUD/DATOS/CIRUGPROG  AS CR
            LEFT JOIN GEMA_MEDICOS/DATOS/MEDICOS AS ME
                ON ME.codigo = CR.medico
            WHERE
                CR.fecha BETWEEN CTOD('%s') AND CTOD('%s')
                AND CR.moti_canc = '  '",
            $from->format('m.d.y'), $to->format('m.d.y')
        ));

        if ($query === false)
            throw new \PDOException("Error en consulta: ". $this->db->errorCode());

        return $this->formatter->forMedicos($query);
    }

    public function ocupacion(): array
    {
        $hoy = date("m.d.y");
        $query = $this->db->query(sprintf(
            "SELECT DISTINCT
                CR.horai AS inicio,
                PA.nombre AS quirofano,
                CR.horfinest AS estimada,
                CR.horfinreal AS final
            FROM GEMA10.D/SALUD/DATOS/CIRUGPROG AS CR
            LEFT JOIN GEMA10.D/IPT/DATOS/PUNTO_AT AS PA
                ON PA.punto_at = CR.lugar
            WHERE
                CR.fecha = CTOD('$hoy')
                AND CR.moti_canc = '  '"
        ));

        if ($query === false)
            throw new \PDOException("Error en consulta: ". $this->db->errorCode());

        return $this->formatter->forOcupacion($query);
    }
}