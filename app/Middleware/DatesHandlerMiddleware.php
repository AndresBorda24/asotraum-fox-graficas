<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;

use function App\responseJson;

/**
 * Este middleware toma las fecha de inicio `start` y de fin `end`
 * de la url y las procesa para usarlas en consultas de FoxPro.
 *
 * Si no se envian fechas toma las del ultimo mes
*/
class DatesHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactory $responseFactory
    ) { }

    public function process(Request $request, RequestHandler $handler): Response
    {
        @[
            "from" => $from,
            "to" => $to
        ] = $request->getQueryParams();

        try {
            $to   = new \DateTime($to ? $to : 'now');
            $from = new \DateTime($from ? $from : '7 days ago');
        } catch (\Exception $e) {
            $response = $this->responseFactory->createResponse();
            return responseJson( $response, [
                "status"  => false,
                "message" => "Las fechas suministradas no son validas"
            ], 422);
        }

        $request = $request
            ->withAttribute("to", $to)
            ->withAttribute("from", $from)
        ;

        return $handler->handle($request);
    }
}
