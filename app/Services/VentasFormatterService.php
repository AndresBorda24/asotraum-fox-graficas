<?php
declare(strict_types=1);

namespace App\Services;

use function App\trimUtf8;

class VentasFormatterService
{
    private array $schema = [];

    /**
     * Da formato a los resultados de la consulta para la grafica de resumen
     * de facturas.
    */
    public function facturado(\PDOStatement $data): void
    {
        $ctrl = $this->schema;

        while($reg = $data->fetch()) {
            if( count($ctrl["total_facturado"]) >= 10 ) {
                $ctrl["categories"][10] = "otros";

                if(! isset($ctrl["total_facturado"][10])) {
                    $ctrl["total_facturado"][10] = 0;
                }

                if(! isset($ctrl["total_facturas"][10])) {
                    $ctrl["total_facturas"][10] = 0;
                }

                $ctrl["total_facturas"][10]  += (int) $reg->total;
                $ctrl["total_facturado"][10] += (
                    (int) $reg->gravado +
                    (int) $reg->exento +
                    (int) $reg->iva
                ) - (int) $reg->copago;

                continue;
            }

            array_push($ctrl["categories"], trimUtf8($reg->nombre));
            array_push($ctrl["total_facturas"], (int) $reg->total);
            array_push($ctrl["total_facturado"],  (
                (int) $reg->gravado +
                (int) $reg->exento +
                (int) $reg->iva
            ) - (int) $reg->copago);
        }

        $this->schema = $ctrl;
    }

    /**
     * @param string $start Fecha de inicio de la consulta
     * @param string $end   Fecha de corte de la consulta
    */
    public function setfacturadoSchema(string $start, string $end): void
    {
        $this->schema = [
            "total_facturado" => [],
            "total_facturas"  => [],
            "categories"      => [],
            "meta"            => [
                "dates" => [
                    "start" => $start,
                    "end"   => $end
                ]
            ]
        ];
    }

    /**
     * Da formato a los resultados de la consulta para la grafica de resumen
     * general de ventas.
    */
    public function resumenGeneral(\PDOStatement $data, string $k): void
    {
        if ($this->schema === []) {
            throw new \RuntimeException("No has setteado el schema...");
        }

        /** Estas variables son para evitar escribir $this->schema[$k] */
        $ctrl         = [];
        $totalCash    = 0;
        $totalRecords = 0;

        while ($d = $data->fetch()) {
            $totalCash    += $d->total;
            $totalRecords += $d->total_records;

            if (count($ctrl) >= 5) {
                if (count($ctrl) === 5) {
                    $ctrl[5] = [
                        "nombre"  => "otros",
                        "records" => 0,
                        "total"   => 0
                    ];
                }

                $ctrl[5]["records"] += (int) $d->total_records;
                $ctrl[5]["total"]   += $d->total;
                continue;
            }

            $ctrl[] = [
                "tercero" => $d->tercero,
                "nombre"  => trimUtf8($d->nombre),
                "records" => (int) $d->total_records,
                "total"   => (int) $d->total
            ];
        }

        $this->schema["meta"]["total"]["records"] += $totalRecords;
        $this->schema["meta"]["total"]["cash"]    += $totalCash;
        $this->schema["data"][$k]["data"] = $ctrl;
        $this->schema["data"][$k]["meta"] = [
            "records" => $totalRecords,
            "total"   => $totalCash
        ];
    }

    /**
     * @param string $start Fecha de inicio de la consulta
     * @param string $end   Fecha de corte de la consulta
    */
    public function setResumenGeneralSchema(string $start, string $end): void
    {
        $this->schema = [
            "data"      => [
                "radicado"       => [],
                "sin-radicacion" => [],
                "liberado"       => [],
                "pendiente"      => []
            ],
            "meta"      => [
                "dates" => [
                    "start" => $start,
                    "end"   => $end
                ],
                "total" => [
                    "records" => 0,
                    "cash"    => 0
                ]
            ]
        ];
    }

    /**
     * Retorna el array formateado con la informacion de las  queries.
    */
    public function getData(): array
    {
        return $this->schema;
    }
}
