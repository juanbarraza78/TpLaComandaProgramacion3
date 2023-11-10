<?php

class Usuario
{
    public $sueldo;
    public $nombreUsuario;
    public $sector;
    public $fechaIngreso;
    public $idUsuario;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (sueldo, sector, fechaIngreso, nombreUsuario) VALUES (:sueldo, :sector, :fechaIngreso, :nombreUsuario)");

        $fecha = new DateTime();
        $fechaFormateada = $fecha->format('Y-m-d');
        
        $consulta->bindValue(':sueldo', $this->sueldo, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':fechaIngreso', $fechaFormateada); // nose si funca con date
        $consulta->bindValue(':nombreUsuario', $this->nombreUsuario, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT sueldo, sector, fechaIngreso, nombreUsuario, idUsuario FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($idUsuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT sueldo, sector, fechaIngreso, nombreUsuario, idUsuario FROM usuarios WHERE idUsuario = :idUsuario");
        $consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function modificarUsuario($sueldo, $sector, $fechaIngreso, $nombreUsuario, $idUsuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET sueldo = :sueldo, sector = :sector, fechaIngreso = :fechaIngreso, nombreUsuario = :nombreUsuario WHERE idUsuario = :idUsuario");
        $consulta->bindValue(':sueldo', $sueldo, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $sector, PDO::PARAM_INT);
        $consulta->bindValue(':fechaIngreso', $fechaIngreso, PDO::PARAM_STR);
        $consulta->bindValue(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
        $consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($idUsuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM usuarios WHERE idUsuario = :idUsuario");
        $consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $consulta->execute();
    }


}