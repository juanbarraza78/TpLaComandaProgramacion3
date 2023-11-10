<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';

class PedidoController extends Pedido 
{
    public function CargarUno($request, $response, $args) // POST : idMesa nombreCliente precio
    {
        $parametros = $request->getParsedBody();

        $idMesa = $parametros['idMesa'];
        $nombreCliente = $parametros['nombreCliente'];
        $precio = $parametros['precio'];

        $pedido = new Pedido();
        $pedido->idMesa = $idMesa;
        $pedido->nombreCliente = $nombreCliente;
        $pedido->precio = $precio;


        $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) // GET :  codigoPedido
    {
        $clave = $args['codigoPedido']; 

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

    public function ModificarUno($request, $response, $args) // PUT  idMesa estado nombreCliente precio puntuacion comentario codigoPedido
    {
        $parametros = $request->getParsedBody();

        $idMesa = $parametros['idMesa'];
        $estado = $parametros['estado'];
        $nombreCliente = $parametros['nombreCliente'];
        $precio = $parametros['precio'];
        $puntuacion = $parametros['puntuacion'];
        $comentario = $parametros['comentario'];
        $codigoPedido = $parametros['codigoPedido'];

        Pedido::modificarPedido($idMesa, $estado, $nombreCliente, $precio, $puntuacion, $comentario, $codigoPedido);

        $payload = json_encode(array("mensaje" => "Pedido Modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function ModificarEstado($request, $response, $args) // PUT  estado codigoPedido
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $codigoPedido = $parametros['codigoPedido'];

        Pedido::modificarEstadoDelPedido($estado, $codigoPedido);

        $payload = json_encode(array("mensaje" => "El estado del pedido se modifico con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) // DELETE 
    {

        $codigoPedido = $args['codigoPedido'];
        Pedido::borrarPedido($codigoPedido);

        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function FinalizarPedido($request, $response, $args) // POST codigoPedido
    {
      $parametros = $request->getParsedBody();
      $codigoPedido = $parametros['codigoPedido'];
      Pedido::modificarEstadoDelPedido("Finalizado", $codigoPedido);
      Producto::modificarEstadoDelProducto("Finalizado",$codigoPedido);

      $payload = json_encode(array("mensaje" => "Pedido Finalizado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}