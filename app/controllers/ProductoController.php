<?php
require_once './models/Producto.php';

class ProductosController extends Producto 
{
    public function CargarUno($request, $response, $args) // POST : tiempoEstimado tipo nombre codigoPedido
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

        $payload = json_encode(array("mensaje" => "Producto creado con exito","id " => "{$id}" ));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerUno($request, $response, $args) // GET :  idProducto
    {
        $idProducto = $args['idProducto']; 

        $prod = Producto::obtenerProducto($idProducto);
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

    public function TraerTodosVerificado($request, $response, $args) // GET estado
    {
      $header = $request->getHeaderLine('Authorization');
      $token = trim(explode("Bearer", $header)[1]);
      $data = AutentificadorJWT::ObtenerData($token);

      $parametrosParam = $request->getQueryParams();
      $estado = $parametrosParam['estado'];

      $lista = Producto::obtenerTodosSegunSuEstadoYTipo($estado, $data->sector);

      $payload = json_encode(array("listaProductos" => $lista));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) // PUT  estado tiempoEstimado tiempoReal tipo nombre codigoPedido idProducto
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $tiempoEstimado = $parametros['tiempoEstimado'];
        $tiempoReal = $parametros['tiempoReal'];
        $tipo = $parametros['tipo'];
        $nombre = $parametros['nombre'];
        $codigoPedido = $parametros['codigoPedido'];
        $idProducto = $parametros['idProducto'];

        Producto::modificarProducto($estado, $tiempoEstimado, $tiempoReal, $tipo, $nombre, $codigoPedido, $idProducto);

        $payload = json_encode(array("mensaje" => "Producto Modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function ModificarEstadoEnPreparacion($request, $response, $args) // PUT estado tiempoEstimado idProducto
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $tiempoEstimado = $parametros['tiempoEstimado'];
        $idProducto = $parametros['idProducto'];

        Producto::modificarEstadoDelProductoPendiente($estado, $tiempoEstimado, $idProducto);

        $payload = json_encode(array("mensaje" => "El estado del producto fue modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarEstadoListoParaServir($request, $response, $args) // PUT estado tiempoReal idProducto
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $tiempoReal = $parametros['tiempoReal'];
        $idProducto = $parametros['idProducto'];

        Producto::modificarEstadoDelProductoListoParaServir($estado, $tiempoReal, $idProducto);

        $payload = json_encode(array("mensaje" => "El estado del producto fue modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) // DELETE idProducto
    {

        $idProducto = $args['idProducto'];
        Producto::borrarProducto($idProducto);

        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function GuardarCSV($request, $response, $args) // GET
    {
            
      if($archivo = fopen("csv/productos.csv", "w"))
      {
        $lista = Producto::obtenerTodos();
        foreach( $lista as $producto )
        {
            fputcsv($archivo, [$producto->estado, $producto->tiempoEstimado, $producto->tiempoReal, $producto->tipo, $producto->nombre, $producto->codigoPedido, $producto->idProducto]);
        }
        fclose($archivo);
        $payload =  json_encode(array("mensaje" => "La lista de productos se guardo correctamente"));
      }
      else
      {
        $payload =  json_encode(array("mensaje" => "No se pudo abrir el archivo de productos"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function CargarCSV($request, $response, $args) // GET
    {
      if(($archivo = fopen("csv/productos.csv", "r")) !== false)
      {
        Producto::borrarPedidos();
        while (($filaProducto = fgetcsv($archivo, 0, ',')) !== false) //esto seria como un while !feof
        {
          $nuevoProducto = new Producto();
          $nuevoProducto->estado = $filaProducto[0];
          $nuevoProducto->tiempoEstimado = $filaProducto[1];
          $nuevoProducto->tiempoReal = $filaProducto[2];
          $nuevoProducto->tipo = $filaProducto[3];
          $nuevoProducto->nombre = $filaProducto[4];
          $nuevoProducto->codigoPedido = $filaProducto[5];
          $nuevoProducto->idProducto = $filaProducto[6];
          $nuevoProducto->crearProductoCSV();
        }
        fclose($archivo);
        $payload  =  json_encode(array("mensaje" => "Los productos se cargaron correctamente"));
      }
      else
      {
        $payload =  json_encode(array("mensaje" => "No se pudo leer el archivo de productos"));
      }
                
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }


}