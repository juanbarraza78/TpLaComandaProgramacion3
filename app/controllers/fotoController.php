<?php

require_once './models/Pedido.php';
class fotoController
{
    public function SubirFoto($request, $response, $args) // POST : codigoPedido + foto
    {
        $parametros = $request->getParsedBody();

        $codigoPedido = $parametros['codigoPedido'];
        $pedidoAux = Pedido::obtenerPedido($codigoPedido);

        $carpeta_archivos = 'Img/';
        $nombre_archivo = $pedidoAux->nombreCliente . $pedidoAux->codigoPedido;
        $ruta_destino = $carpeta_archivos . $nombre_archivo . ".jpg";

        if (move_uploaded_file($_FILES['archivo']['tmp_name'],  $ruta_destino))
        {
            // linkear pedido a la foto
            $payload = json_encode(array("mensaje" => "Foto Subida con exito"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se pudo guardar la Foto"));
        }  

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}