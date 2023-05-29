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

        while ($reg = $data->fetch()) {
            $ctrl["meta"]["total"]["records"] += (int) $reg->facturas;
            $ctrl["meta"]["total"]["cash"]    += (int) $reg->total;


            if (count($ctrl["data"]) >= 10) {
                if (!array_key_exists("otros", $ctrl["data"])) {
                    $ctrl["data"]["otros"] = [
                        "facturas" => 0,
                        "total"    => 0
                    ];
                }

                $ctrl["data"]["otros"]["facturas"] += (int) $reg->facturas;
                $ctrl["data"]["otros"]["total"]    += (int) $reg->total;

                continue;
            }

            $ctrl["data"][trimUtf8($reg->nombre)] = [
                "facturas" => (int) $reg->facturas,
                "total"    => (int) $reg->total
            ];
        }

        $this->schema = $ctrl;
    }

    /**
     * @param string $start Fecha de inicio de la consulta
     * @param string $end   Fecha de corte de la consulta
     */
    public function setFacturadoSchema(string $start, string $end): void
    {
        $this->schema = [
            "data"      => [],
            "meta"      => [
                "total" => [
                    "records" => 0,
                    "cash"    => 0
                ],
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

        $d = $data->fetch();

        $this->schema["meta"]["total"]["records"] += ($d === false)
            ? 0 : $d->total_records;
        $this->schema["meta"]["total"]["cash"]    += ($d === false)
            ? 0 : $d->total;
        $this->schema["data"][$k] = [
            "records" => ($d === false) ? 0 : $d->total_records,
            "total"   => ($d === false) ? 0 : $d->total
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
