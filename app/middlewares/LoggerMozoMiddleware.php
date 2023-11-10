<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LoggerMozoMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        
        $parametros = $request->getQueryParams();
        if(isset($parametros['sector']))
        {   
            $sector = $parametros['sector'];
            if ($sector === 'mozo' || $sector === "socio") {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No sos Mozo o Socio'));
                $response->getBody()->write($payload);
            }
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No existe el parametro sector'));
            $response->getBody()->write($payload);
        }

        return $response;
    }
}