<?php

include "cliente.php";

if(isset($_POST['Nombre']) && isset($_POST['Apellido']) && isset($_POST['Tipo_Documento']) && 
isset($_POST['Nro_Documento']) && isset($_POST['Email']) && isset($_POST['Tipo_de_Cuenta']) && 
isset($_POST['Moneda']))
{
    if(($_POST['Tipo_de_Cuenta'] == 'CA' || $_POST['Tipo_de_Cuenta'] == 'CC') &&
    ($_POST["Moneda"] == '$' || $_POST["Moneda"] == 'U$S'))
    {
        $clienteAux;
        if(isset($_POST['Saldo_Inicial']))
        {
            
            $clienteAux = new Cliente($_POST["Nombre"],$_POST["Apellido"],$_POST["Tipo_Documento"],$_POST["Nro_Documento"],$_POST["Email"],$_POST["Tipo_de_Cuenta"],$_POST["Moneda"],$_POST["Saldo_Inicial"]);   
        }
        else
        {
            $clienteAux = new Cliente($_POST["Nombre"],$_POST["Apellido"],$_POST["Tipo_Documento"],$_POST["Nro_Documento"],$_POST["Email"],$_POST["Tipo_de_Cuenta"],$_POST["Moneda"]);
        }
        //$clienteAux = Cliente::AgregarCliente($clienteAux)

        if(Cliente::AgregarCliente($clienteAux) == false)
        {
            //Subir Foto
            $carpeta_archivos = 'ImagenesDeCuentas/2023/';
            $nombre_archivo = $clienteAux->_nroDeCuenta . $clienteAux->_tipoDeCuenta;
            $ruta_destino = $carpeta_archivos . $nombre_archivo . ".jpg";

            if (move_uploaded_file($_FILES['archivo']['tmp_name'],  $ruta_destino))
            {
                echo "El archivo ha sido cargado correctamente.";
            }
            else
            {
                echo "Ocurrió algún error al subir el fichero. No pudo guardarse.";
            }   
        }
        else
        {
            echo "Se actualizo el cliente.";
        }
       
    }
    else
    {
        echo 'Tipo de Cuenta y/o Moneda invalida';
    }
}
    
?>