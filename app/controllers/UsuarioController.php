<?php
require_once './models/Usuario.php';

class UsuarioController extends Usuario 
{
    public function CargarUno($request, $response, $args) // POST : sueldo sector fechaIngreso nombreUsuario
    {
        $parametros = $request->getParsedBody();

        $sueldo = $parametros['sueldo'];
        $sector = $parametros['sector'];
        // $fechaIngreso = $parametros['fechaIngreso'];
        $nombreUsuario = $parametros['nombreUsuario'];

        $user = new Usuario();
        $user->sueldo = $sueldo;
        $user->sector = $sector;
        // $user->fechaIngreso = $fechaIngreso;
        $user->nombreUsuario = $nombreUsuario;

        $id = $user->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito", "id" => $id));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerUno($request, $response, $args) // GET :  idUsuario
    {
        $idUsuario = $args['idUsuario']; 

        $user = Usuario::obtenerUsuario($idUsuario);
        $payload = json_encode($user);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) // GET 
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuarios" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}