<?php

require_once './models/Producto.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ModificarEstadoProductoMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

        $parametros = $request->getParsedBody();
        $idProducto = $parametros['idProducto'];
        
        $producAux = Producto::obtenerProducto($idProducto);
        
        if ($producAux->tipo === $data->sector) {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'El sector y la comida no son compatibles'));
            $response->getBody()->write($payload);
        }
        return $response;
    }
}