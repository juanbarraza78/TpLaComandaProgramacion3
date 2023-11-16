<?php
require_once './models/Usuario.php';
require_once './utils/AutentificadorJWT.php';

class UsuarioController extends Usuario 
{
    public function CargarUno($request, $response, $args) // POST : sueldo sector fechaIngreso nombreUsuario contrasenia
    {
        $parametros = $request->getParsedBody();

        $sueldo = $parametros['sueldo'];
        $sector = $parametros['sector'];
        $nombreUsuario = $parametros['nombreUsuario'];
        $contrasenia = $parametros['contrasenia'];


        $user = new Usuario();
        $user->sueldo = $sueldo;
        $user->sector = $sector;
        $user->nombreUsuario = $nombreUsuario;
        $user->contrasenia = $contrasenia;

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

    public function ModificarUno($request, $response, $args) // POST  sueldo nombreUsuario sector fechaIngreso idUsuario, contrasenia
    {
        $parametros = $request->getParsedBody();

        $sueldo = $parametros['sueldo'];
        $nombreUsuario = $parametros['nombreUsuario'];
        $sector = $parametros['sector'];
        $fechaIngreso = $parametros['fechaIngreso'];
        $idUsuario = $parametros['idUsuario'];
        $contrasenia = $parametros['contrasenia'];

        Usuario::modificarUsuario($sueldo, $sector, $fechaIngreso, $nombreUsuario, $idUsuario, $contrasenia);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) // DELETE idUsuario
    {

        $idUsuario = $args['idUsuario'];
        Usuario::borrarUsuario($idUsuario);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function LoginUsuario($request, $response, $args) // POST nombreUsuario contrasenia
    {
      $parametros = $request->getParsedBody();

      $nombreUsuario = $parametros['nombreUsuario'];
      $contrasenia = $parametros['contrasenia'];

      $existe = false;
      $lista = Usuario::obtenerTodos();

      foreach ($lista as $usuario) {
        if($usuario->nombreUsuario == $nombreUsuario && $usuario->contrasenia == $contrasenia)
        {
          $existe = true;
          $idUsuario = $usuario->idUsuario;
          $sector = $usuario->sector;
        }
      }
      if($existe)
      {
        $datos=array('idUsuario' => $idUsuario,'sector' => $sector);
        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token));
      }
      else
      {
        $payload = json_encode(array('error' => 'Nombre Usuario o contraseña incorrectos'));
      }

      $response->getBody()->write($payload);

      return $response->withHeader('Content-Type', 'application/json');

    }

   
  


}