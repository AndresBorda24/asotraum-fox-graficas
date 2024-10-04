<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\Admisiones;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function App\responseJson;

/**
 * Controlador encargado de los datos para las estadísticas del módulo
 * de ventas.
 */
class AdmisionesController
{
    public function __construct(
        private Admisiones $adm
    ) {}

    /**
     * Obtiene las admisiones agrupadas por horas y Clasepro.
     */
    public function getDataPorHora(Request $request, Response $response): Response
    {
        // Obtener los datos desde el modelo
        $selectedDays = $request->getQueryParams()['selectedDays'] ?? '';

        // Convertir la cadena de tipos de ingreso a un array
        $selectedDaysArray = explode(',', $selectedDays);
        $data = $this->adm->getClaseproPorHora($selectedDaysArray);
        // Inicializar la estructura para la respuesta
        $responseData = [
            'data' => [],
            'meta' => [
                'fechas' => []
            ]
        ];

        // Agrupar los datos por fecha y hora
        foreach ($data as $row) {
            $fecha = trim($row['fecha']);
            $hora = trim($row['hora']);
            $clasepro = intval($row['clasepro']);

            // Verificar si ya existe la fecha en el array
            if (!isset($responseData['data'][$fecha])) {
                // Inicializar las horas con 0
                $responseData['data'][$fecha] = array_fill(0, 24, 0); // Crea un array con 24 horas inicializadas a 0
                $responseData['meta']['fechas'][] = $fecha; // Agregar la fecha a las metas
            }

            // Extraer la hora en formato de 24 horas
            $hora_index = intval(date('H', strtotime($hora))); // Obtener el índice de la hora (0-23)

            // Aumentar el contador para la hora correspondiente
            $responseData['data'][$fecha][$hora_index]++; // Incrementar el conteo
        }

        // Convertir las horas a formato HH:00 para la respuesta
        $formattedResponse = [];
        foreach ($responseData['data'] as $fecha => $horas) {
            foreach ($horas as $hour => $count) {
                $formattedResponse[$fecha][sprintf("%02d:00", $hour)] = $count; // Usar el formato HH:00
            }
        }

        // Crear la respuesta JSON
        $response->getBody()->write(json_encode(['data' => $formattedResponse, 'meta' => $responseData['meta']]));

        // Agregar el header para indicar que es JSON
        return $response->withHeader('Content-Type', 'application/json');
    }


}