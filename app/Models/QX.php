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
                CR.fecha BETWEEN CTOD('%s') AND CTOD('%s')",
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
                PA.nombre As lugar_nombre,
                CR.moti_canc AS motivo_cod,
                MC.nombre AS motivo_cancelacion
            From GEMA10.D/SALUD/DATOS/CIRUGPROG  As CR
            Left Join GEMA10.D/IPT/DATOS/PUNTO_AT As PA
                On CR.lugar = PA.PUNTO_AT
            LEFT JOIN gema10.d\SALUD\DATOS\MOTIV_CANC AS MC
                ON CR.moti_canc = MC.codigo
            WHERE
                CR.fecha BETWEEN CTOD('%s') AND CTOD('%s')
                AND CR.moti_canc != '  '",
            $from->format('m.d.y'), $to->format('m.d.y')
        ));

        if ($query === false)
            throw new \PDOException("Error en consulta: ". $this->db->errorCode());

        return $this->formatter->forMotivosCancelacion($query);
    }
}