<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ModificarEstadoMesaMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        
        $parametrosParam = $request->getQueryParams();
        $parametrosBody = $request->getParsedBody();
        

        if(isset($parametrosParam['sector']) && isset($parametrosBody['estado']))
        {   
            $sector = $parametrosParam['sector'];
            $estado = $parametrosBody['estado'];

            if((($estado == "con cliente esperando pedido" || $estado == "con cliente comiendo" || $estado == "con cliente pagando") && $sector === 'mozo') || $sector === 'socio')
            {
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
            $payload = json_encode(array('mensaje' => 'No existe el parametro sector o Estado'));
            $response->getBody()->write($payload);
        }

        return $response;
    }
}