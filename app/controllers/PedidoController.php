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
    $codigoPedido = $args['codigoPedido'];

    $pedido = Pedido::obtenerPedido($codigoPedido);
    $payload = json_encode($pedido);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUnoCliente($request, $response, $args) // GET :  codigoPedido idMesa
  {
    $parametrosParam = $request->getQueryParams();
    
    $codigoPedido = $parametrosParam['codigoPedido'];
    $idMesa = $parametrosParam['idMesa'];

    $pedido = Pedido::obtenerPedido($codigoPedido);
    if ($pedido->idMesa == $idMesa) {
      $payload = json_encode($pedido);
    } else {
      $payload = json_encode(array("mensaje" => "No existe ese Pedido en esa Mesa"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUnoCliente($request, $response, $args) // PUT :  codigoPedido idMesa puntuacion comentario
  {
    $parametros = $request->getParsedBody();
    $codigoPedido = $parametros['codigoPedido'];
    $idMesa = $parametros['idMesa'];
    $puntuacion = $parametros['puntuacion'];
    $comentario = $parametros['comentario'];

    $pedido = Pedido::obtenerPedido($codigoPedido);
    if ($pedido->idMesa == $idMesa) 
    {
      Pedido::modificarComentarioYPuntuacionDelPedido($puntuacion, $comentario, $codigoPedido);
      $payload = json_encode(array("mensaje" => "Se Agregaron los comentarios y la puntuacion"));
    } else 
    {
      $payload = json_encode(array("mensaje" => "No existe ese Pedido en esa Mesa"));
    }
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
  public function TraerTodosSegunEstado($request, $response, $args) // GET estado
  {
    $parametrosParam = $request->getQueryParams();
    $estado = $parametrosParam['estado'];

    $lista = Pedido::obtenerTodosSegunEstado($estado);
    $payload = json_encode(array("listaPedidos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args) // PUT  idMesa estado nombreCliente precio puntuacion comentario codigoPedido tiempoDemora
  {
    $parametros = $request->getParsedBody();

    $idMesa = $parametros['idMesa'];
    $estado = $parametros['estado'];
    $nombreCliente = $parametros['nombreCliente'];
    $precio = $parametros['precio'];
    $puntuacion = $parametros['puntuacion'];
    $comentario = $parametros['comentario'];
    $codigoPedido = $parametros['codigoPedido'];
    $tiempoDemora = $parametros['tiempoDemora'];

    Pedido::modificarPedido($idMesa, $estado, $nombreCliente, $precio, $puntuacion, $comentario, $codigoPedido, $tiempoDemora);

    $payload = json_encode(array("mensaje" => "Pedido Modificado con exito"));

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

  public function PedidoEnPreparacion($request, $response, $args) // POST codigoPedido
  {
    $parametros = $request->getParsedBody();
    $codigoPedido = $parametros['codigoPedido'];

    $lista = Producto::obtenerTodosSegunSuCodigoPedido($codigoPedido);
    $existe = true;
    foreach ($lista as $producto) {
      if ($producto->estado !== "En Preparacion") {
        $existe = false;
        break;
      }
    }
    if ($existe) {
      $esPrimero = true;
      foreach ($lista as $producto) {
        if ($producto->estado === "En Preparacion") {
          if ($esPrimero || $mayorDemora < $producto->tiempoEstimado) {
            $mayorDemora = $producto->tiempoEstimado;
            $esPrimero = false;
          }
        }
      }
      Pedido::modificarTiempoDeDemoraDelPedido($mayorDemora, $codigoPedido);
      Pedido::modificarEstadoDelPedido("En Preparacion", $codigoPedido);
      $payload = json_encode(array("mensaje" => "El estado del pedido cambio a En Preparacion correctamente"));
    } else {
      $payload = json_encode(array("mensaje" => "No se pudo cambiar el estado del pedido"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function PedidoListoParaServir($request, $response, $args) // POST codigoPedido
  {
    $parametros = $request->getParsedBody();
    $codigoPedido = $parametros['codigoPedido'];

    $lista = Producto::obtenerTodosSegunSuCodigoPedido($codigoPedido);
    $existe = true;
    foreach ($lista as $producto) {
      if ($producto->estado !== "Listo Para Servir") {
        $existe = false;
        break;
      }
    }
    if ($existe) 
    {
      $esPrimero = true;
      foreach ($lista as $producto) {
        if ($producto->estado === "Listo Para Servir") {
          if ($esPrimero || $mayorDemora < $producto->tiempoReal) {
            $mayorDemora = $producto->tiempoReal;
            $esPrimero = false;
          }
        }
      }
      Pedido::modificarTiempoDeDemoraDelPedido($mayorDemora, $codigoPedido);
      Pedido::modificarEstadoDelPedido("Listo Para Servir", $codigoPedido);
      $payload = json_encode(array("mensaje" => "El estado del pedido cambio a Listo Para Servir correctamente"));
    } 
    else 
    {
      $payload = json_encode(array("mensaje" => "No se pudo cambiar el estado del pedido"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}