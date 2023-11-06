<?php

class Pedido
{
    // nose que usar si quiero pasar un json
    public $arrayProductos;
    public $idMesa;
    public $imgMesa;
    public $estado;
    public $nombreCliente ;
    public $precio ;
    public $resenia ;
    public $idPedido ;
    public $clave;
    
    
    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (arrayProductos, idMesa, imgMesa, estado, nombreCliente, precio, resenia, clave) VALUES (:arrayProductos, :idMesa, :imgMesa, :estado, :nombreCliente, :precio, :resenia, :clave)");
        
        $clave = $this->idPedido.$this->nombreCliente;

        $consulta->bindValue(':arrayProductos', $this->arrayProductos, PDO::PARAM_STR);
        // $consulta->bindValue(':arrayProductos', "null", PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':imgMesa', $this->imgMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT); // no hay float? 
        $consulta->bindValue(':resenia', $this->resenia, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();

        // agregar comida -> agregarlo al array comida
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consulta = $objAccesoDatos->prepararConsulta("SELECT arrayProductos, idMesa, imgMesa, estado, nombreCliente, precio, resenia, idPedido, clave FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($clave) // busco por clave
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT arrayProductos, idMesa, imgMesa, estado, nombreCliente, precio, resenia, idPedido, clave FROM pedidos WHERE clave = :clave");
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public function modificarPedido() // nose si es mejor estatico
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET arrayProductos = :arrayProductos, idMesa = :idMesa, imgMesa = :imgMesa, estado = :estado, nombreCliente = :nombreCliente, precio = :precio, resenia = :resenia ,clave = :clave WHERE idPedido = :idPedido");
        $consulta->bindValue(':arrayProductos', $this->arrayProductos, PDO::PARAM_STR); // nose que usar si quiero pasar un json
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':imgMesa', $this->imgMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':resenia', $this->resenia, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarPedido($idPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM pedidos WHERE idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();
    }

}