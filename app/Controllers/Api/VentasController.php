<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\ConnectionFox;
use Psr\Http\Message\ResponseInterface as Response;

use function App\responseJson;

/**
 * Controlador encargado de los datos para las estadisticas del modulo
 * de ventas.
*/
class VentasController
{
    public function __construct (
        private ConnectionFox $conn
    ){}

    public function test(Response $response): Response
    {
        $data = $this->conn->query("
            SELECT *
            FROM GEMA10.D/VENTAS/DATOS/VTFACC18
            WHERE pedido = 537584
        ")->fetchAll();

        return responseJson($response, $data);
    }
}

