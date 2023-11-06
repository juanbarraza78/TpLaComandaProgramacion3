<?php
require_once './models/Producto.php';

class ProductosController extends Producto 
{
    public function CargarUno($request, $response, $args) // POST : estado tiempo tipo nombre
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $tiempo = $parametros['tiempo'];
        $tipo = $parametros['tipo'];
        $nombre = $parametros['nombre'];

        $prod = new Producto();
        $prod->estado = $estado;
        $prod->tiempo = $tiempo;
        $prod->tipo = $tipo;
        $prod->nombre = $nombre;

        $id = $prod->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito", "id" => $id));

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
}