<?php
require_once './models/Pedido.php';

class PedidoController extends Pedido 
{
    public function CargarUno($request, $response, $args) // POST : arrayProductos idMesa imgMesa estado nombreCliente precio resenia
    {
        $parametros = $request->getParsedBody();

        $arrayProductos = $parametros['arrayProductos'];
        $idMesa = $parametros['idMesa'];
        $imgMesa = $parametros['imgMesa'];
        $estado = $parametros['estado'];
        $nombreCliente = $parametros['nombreCliente'];
        $precio = $parametros['precio'];
        $resenia = $parametros['resenia'];

        $pedido = new Pedido();
        $pedido->arrayProductos = $arrayProductos;
        $pedido->idMesa = $idMesa;
        $pedido->imgMesa = $imgMesa;
        $pedido->estado = $estado;
        $pedido->nombreCliente = $nombreCliente;
        $pedido->precio = $precio;
        $pedido->resenia = $resenia;

        $id = $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito", "id" => $id));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) // GET :  clave
    {
        $clave = $args['clave']; 

        $pedido = Pedido::obtenerPedido($clave);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) // GET 
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}