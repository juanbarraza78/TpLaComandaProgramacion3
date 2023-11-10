<?php
require_once './models/Producto.php';

class ProductosController extends Producto 
{
    public function CargarUno($request, $response, $args) // POST : tipo nombre codigoPedido
    {
        $parametros = $request->getParsedBody();

        $tipo = $parametros['tipo'];
        $nombre = $parametros['nombre'];
        $codigoPedido = $parametros['codigoPedido'];

        $prod = new Producto();
        $prod->tipo = $tipo;
        $prod->nombre = $nombre;
        $prod->codigoPedido = $codigoPedido;

        $id = $prod->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerUno($request, $response, $args) // GET :  codigoPedido
    {
        $codigoPedido = $args['codigoPedido']; 

        $prod = Producto::obtenerProducto($codigoPedido);
        $payload = json_encode($prod);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) // GET 
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) // PUT  estado tiempoEstimado tiempoReal tipo nombre codigoPedido
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $tiempoEstimado = $parametros['tiempoEstimado'];
        $tiempoReal = $parametros['tiempoReal'];
        $tipo = $parametros['tipo'];
        $nombre = $parametros['nombre'];
        $codigoPedido = $parametros['codigoPedido'];

        Producto::modificarProducto($estado, $tiempoEstimado, $tiempoReal, $tipo, $nombre, $codigoPedido);

        $payload = json_encode(array("mensaje" => "Producto Modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function ModificarEstado($request, $response, $args) // PUT  estado codigoPedido
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $codigoPedido = $parametros['codigoPedido'];

        Producto::modificarEstadoDelProducto($estado, $codigoPedido);

        $payload = json_encode(array("mensaje" => "El estado del producto fue modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) // DELETE codigoPedido
    {

        $codigoPedido = $args['codigoPedido'];
        Producto::borrarProducto($codigoPedido);

        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}