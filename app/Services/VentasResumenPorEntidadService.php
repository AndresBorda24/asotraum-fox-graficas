<?php
declare(strict_types=1);

namespace App\Services;

use App\ConnectionFox;

use function App\trimUtf8;

/**
 * Este servicio se encarga de realizar la conulta para la grafica de
 * `resumen-por-entidad` de ventas
*/
class VentasResumenPorEntidadService
{
    /**
     * Year del cual se tomara la info
    */
    private string $year;

    /**
     * La consulta basica
    */
    private string $query;

    /**
     * Representa el top 10 terceros
    */
    public ?array $top = null;

    /**
     * Representa el resultado. Este array se ira poblando con los metodos
     * correspondientes
    */
    public  array $data=[];

    /**
     * Una vez obtenido el top 10 de los terceros se crea una condicion
     * para que solo se realice la busqueda dependiendo de estos
     * terceros. EJ: AND ( tercero = 871293681 OR tercero = 803197236...
    */
    private string $andTerceros = '';

    /**
     * @param string $start Fecha de Inicio
     * @param string $end Fecha de corte.
    */
    function __construct(
        private ConnectionFox $db,
        private string $start,
        private string $end,
    ) {
        $this->year  = substr($this->start, 6);
        $this->query = "SELECT
                V.tercero, (
                    SUM(V.vr_gravado) +
                    SUM(V.vr_exento)  +
                    SUM(V.iva_bienes)
                ) - SUM(V.financ_vr) AS total
            FROM GEMA10.D/VENTAS/DATOS/VTFACC$this->year V
            WHERE
                BETWEEN(V.fecha, CTOD('$start'), CTOD('$end'))
                AND ! LIKE('<< ANULADA >>*', observac) %s
            ORDER BY total DESC
            GROUP BY V.tercero";
    }

    /**
     * Realiza las consultas, Organiza la informacion y devuelve un
     * array con los datos
    */
    public function getData(): array
    {
        $this->getTop();
        $this->getRadicado();
        $this->getSinRadicacion();
        $this->getPorRadicar();
        $this->getLiberadas();

        return [
            "data" => $this->data,
            "meta" => [
                "labels" => array_values($this->top),
                "dates"  => [
                    "start" => $this->start,
                    "end"   => $this->end,
                    "year"  => $this->year
                ]
            ]
        ];
    }

    /**
     * Consulta el top 10 terceros que mas facturaron
    */
    private function getTop(int $top = 10)
    {
        $this->top = [];
        try {
            $data = $this->db->query("
                SELECT
                    V.tercero, T.nombre AS nombre, (
                        SUM(V.vr_gravado) +
                        SUM(V.vr_exento)  +
                        SUM(V.iva_bienes)
                    ) - SUM(V.financ_vr) AS total
                FROM GEMA10.D/VENTAS/DATOS/VTFACC$this->year V
                LEFT JOIN GEMA10.D/DGEN/DATOS/TERCEROS T
                    ON V.tercero = T.codigo
                WHERE
                    BETWEEN(V.fecha, CTOD('$this->start'), CTOD('$this->end'))
                    AND ! LIKE('<< ANULADA >>*', observac)
                ORDER BY total DESC
                GROUP BY V.tercero
            ");

            while (($reg = $data->fetch()) && count($this->top) < $top) {
                $this->top[trimUtf8($reg->tercero)] = trimUtf8($reg->nombre);
            }

            unset($data);
            $this->setAndTerceros();
        } catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Obtiene el total facturado por cada tercero en `$top`
    */
    private function getRadicado()
    {
        try {
            $this->checkTopSet();

            $data = $this->db->query(sprintf($this->query, "
                AND radicacion > 0
                AND ! EMPTY(fech_rad)
                " . $this->andTerceros
            ))->fetchAll();

            $this->data["radicado"] = [
                "name" => "radicado",
                "data" => $this->getDataFromResult($data)
            ];
        } catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Obtiene el total facturado por cada tercero en `$top`
    */
    private function getSinRadicacion()
    {
        try {
            $this->checkTopSet();

            $data = $this->db->query(sprintf($this->query, "
                AND radicacion = 0
                " . $this->andTerceros
            ))->fetchAll();

            $this->data["sin-radicacion"] = [
                "name" => "Sin Radicacion",
                "data" => $this->getDataFromResult($data)
            ];
        } catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Obtiene el total facturado por cada tercero en `$top`
    */
    private function getPorRadicar()
    {
        try {
            $this->checkTopSet();

            $data = $this->db->query(sprintf($this->query, "
                AND radicacion > 0
                AND EMPTY(fech_rad)
                " . $this->andTerceros
            ))->fetchAll();

            $this->data["por-radicar"] = [
                "name" => "Pendiente Por Radicar",
                "data" => $this->getDataFromResult($data)
            ];
        } catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Obtiene el total facturado por cada tercero en `$top`
    */
    private function getLiberadas()
    {
        try {
            $this->checkTopSet();

            $data = $this->db->query(sprintf($this->query, "
                AND radicacion = -2
                " . $this->andTerceros
            ))->fetchAll();

            $this->data["liberadas"] = [
                "name" => "Liberadas",
                "data" => $this->getDataFromResult($data)
            ];
        } catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Obtiene la condicion de los OR en base a los terceros en `top`
    */
    private function setAndTerceros(): void
    {
        try {
            $this->checkTopSet();
            if (count($this->top) === 0) return;

            $this->andTerceros = "AND (tercero = ";
            $this->andTerceros .= implode(
                " OR tercero = ",
                array_keys($this->top)
            ) . ")";
        } catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Organiza la informacion del resultado de la consulta y devuelve
     * un array en el orden de `top`
    */
    private function getDataFromResult(array $data): array
    {
        $ctrl = [];

        $data = array_reduce($data, function($acc, $d) {
            $acc[trimUtf8($d->tercero)] = $d->total;
            return $acc;
        }, []);

        foreach ($this->top as $nit => $nombre) {
            array_push($ctrl, (int) @$data[$nit]);
        }

        return $ctrl;
    }

    /**
     * Revisa si ya se ha seteado `top`. Si no, lanza un error
    */
    private function checkTopSet(): void
    {
        if (null === $this->top) {
            throw new \RuntimeException("Top is Missing");
        }
    }
}
