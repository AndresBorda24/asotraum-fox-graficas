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
     * Da formato a la consulta de top facturadores
    */
    public function topFacturadores(\PDOStatement $data): void
    {
        $ctrl = [];

        while (($reg = $data->fetch()) && count($ctrl) <= 15) {
            array_push($ctrl, [
                "id"    => trimUtf8($reg->quien),
                "quien" => trimUtf8($reg->nombre),
                "cuanto" => (int) $reg->total,
                "facturas" => (int) $reg->totalconteo
            ]);
        }
        $this->schema["data"] = $ctrl;
    }

    /**
     * Establece el esquema para la informacion de los facturadores
    */
    public function setTopFacturadoresSchema(string $start, string $end): void
    {
        $year  = substr($start, 6);

        $this->schema = [
            "data" => [],
            "meta" => [
                "dates" => [
                    "start" => $start,
                    "end"   => $end,
                    "year"  => $year
                ]
            ]
        ];
    }

    /**
     * Organiza la informacion para la grilla.
    */
    public function grilla(\PDOStatement $data): void
    {
        $ctrl = [];

        while ($reg = $data->fetch()) {
            $fech_rad = trimUtf8($reg->fech_rad);

            array_push($ctrl, [
                trimUtf8($reg->tercero),
                trimUtf8($reg->nombre_t),
                trimUtf8($reg->nombre_q),
                trimUtf8($reg->fecha),
                ($fech_rad === '1899-12-30') ? null : $fech_rad,
                trimUtf8($reg->radicacion),
                "$ " . number_format((int) $reg->total),
                trimUtf8($reg->observac)
            ]);
        }

        $this->schema["data"] = $ctrl;
    }

    /**
     * Esquema para la informacion de la grilla
    */
    public function setGrillaSchema(string $start, string $end): void
    {
        $year  = substr($start, 6);

        $this->schema = [
            "data" => [],
            "meta" => [
                "columns" => [
                    "Tercero",
                    "Nom.Tercero",
                    "Quien",
                    "Fecha",
                    "Fecha.Rad",
                    "Radicacion",
                    "Valor Factura",
                    "Observacion",
                ],
                "dates" => [
                    "start" => $start,
                    "end"   => $end,
                    "year"  => $year
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
