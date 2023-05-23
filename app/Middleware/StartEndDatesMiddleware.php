<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * Este middleware toma las fecha de inicio `start` y de fin `end`
 * de la url y las procesa para usarlas en consultas de FoxPro.
 *
 * Si no se envian fechas toma las del ultimo mes
*/
class StartEndDatesMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $query = $request->getQueryParams();

        $start = array_key_exists("start", $query)
            ? date("m.d.y", strtotime($query["start"]))
            : date("m.d.y", strtotime('first day of last month'));

        $end = array_key_exists("end", $query)
            ? date("m.d.y", strtotime($query["end"]))
            : date("m.d.y", strtotime('last day of last month'));

        $request = $request
            ->withAttribute("start", $start)
            ->withAttribute("end", $end);

        return $handler->handle($request);
    }
}
