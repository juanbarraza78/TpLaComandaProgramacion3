<?php

class Pedido
{
    public $idMesa;
    public $estado;
    public $nombreCliente;
    public $precio;
    public $puntuacion;
    public $comentario;
    public $codigoPedido;
    public $tiempoDemora;
    
    
    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $clave = $this->generarCodigoAlfanumericoAleatorio();

        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos ( idMesa, estado, nombreCliente, precio, codigoPedido) VALUES (:idMesa, :estado, :nombreCliente, :precio, :codigoPedido)");
        
        $estado = "Pendiente";
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':codigoPedido', $clave, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public function crearPedidoCSV()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $clave = $this->generarCodigoAlfanumericoAleatorio();

        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos ( idMesa, estado, nombreCliente, precio, puntuacion, comentario, codigoPedido, tiempoDemora) VALUES (:idMesa, :estado, :nombreCliente, :precio, :puntuacion, :comentario, :codigoPedido, :tiempoDemora)");
        
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacion', $this->puntuacion, PDO::PARAM_INT);
        $consulta->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoDemora', $this->tiempoDemora, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function borrarPedidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("TRUNCATE pedidos");
        $consulta->execute();
    }
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, estado, nombreCliente, precio, puntuacion, comentario, codigoPedido, tiempoDemora FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerTodosSegunEstado($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, estado, nombreCliente, precio, puntuacion, comentario, codigoPedido, tiempoDemora FROM pedidos WHERE estado = :estado");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerTodosSegunIdMesa($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, estado, nombreCliente, precio, puntuacion, comentario, codigoPedido, tiempoDemora FROM pedidos WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, estado, nombreCliente, precio, puntuacion, comentario, codigoPedido, tiempoDemora FROM pedidos WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function modificarPedido($idMesa, $estado, $nombreCliente, $precio, $puntuacion, $comentario, $codigoPedido, $tiempoDemora)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET idMesa = :idMesa, estado = :estado, nombreCliente = :nombreCliente, precio = :precio, puntuacion = :puntuacion, comentario = :comentario, tiempoDemora = :tiempoDemora  WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $precio, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacion', $puntuacion, PDO::PARAM_INT);
        $consulta->bindValue(':comentario', $comentario, PDO::PARAM_STR);
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoDemora', $tiempoDemora, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function modificarEstadoDelPedido($estado, $codigoPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();
    }
    public static function modificarTiempoDeDemoraDelPedido($tiempoDemora, $codigoPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET tiempoDemora = :tiempoDemora WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':tiempoDemora', $tiempoDemora, PDO::PARAM_INT);
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();
    }
    public static function modificarComentarioYPuntuacionDelPedido($puntuacion, $comentario, $codigoPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET puntuacion = :puntuacion, comentario = :comentario WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':puntuacion', $puntuacion, PDO::PARAM_INT);
        $consulta->bindValue(':comentario', $comentario, PDO::PARAM_STR);
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarPedido($codigoPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM pedidos WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_INT);
        $consulta->execute();
    }
    
    private function generarCodigoAlfanumericoAleatorio($longitud = 5) 
    {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = '';
        $max = strlen($caracteres) - 1;

        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[random_int(0, $max)];
        }

        return $codigo;
    }
}