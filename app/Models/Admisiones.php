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
     * Obtiene todos los datos de Clasepro agrupados con horas.
     * @return array
     */
    public function getClaseproPorHora(array $selectedDays): array
    {
        $selectedDays = array_map(fn($i)=>"'$i'", $selectedDays);
        $selectedDaysList = implode(',', $selectedDays);

        if (!empty($selectedDays)) {
            $query = $this->db->query(
                "SELECT Fecha, Clasepro, Hora
                FROM Z:\GEMA10.D\IPT\DATOS\PTOTC00
                WHERE Fecha IN (DATE() - 2, DATE() - 1, DATE()) 
                AND Clasepro IN ($selectedDaysList)             
                "
            );
        } else if($selectedDays == '') {
            $query = $this->db->query(
                "SELECT Fecha, Clasepro, Hora
                FROM Z:\GEMA10.D\IPT\DATOS\PTOTC00
                WHERE Fecha IN (DATE() - 2, DATE() - 1, DATE()) 
                AND Clasepro IN ('A', '')             
                "
            );
        }


        if ($query === false)
            throw new \PDOException("Error en consulta: ". $this->db->errorCode());

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
