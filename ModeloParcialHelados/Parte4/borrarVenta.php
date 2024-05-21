<?php
include_once "Ventas.php";
include_once "Helado.php";
include_once "Funciones.php";

$_arrayVentas = LeerJSON("Ventas.json");
$_arrayHelados = LeerJSON("heladeria.json");
$_arrayVentas = borrarVenta($_arrayVentas);
GuardarJSON($_arrayVentas, "Ventas.json");

function borrarVenta(array $arrayVentas){
    // Obtener datos de la solicitud PUT
    $datos = json_decode(file_get_contents("php://input"));
    
    // Verificar que se hayan recibido todos los datos necesario
    if(isset($datos->numeroPedido)){
        // Busco la venta correspondiente al número de pedido recibido
        $indexVenta = Venta::BuscarVenta($arrayVentas,$datos->numeroPedido);

        if($indexVenta != -1){
            $nombreArchivo = generarNombreArchivo($arrayVentas,$indexVenta);
            $directorio = 'ImagenesDeLaVenta\\2024\\';
            $directorioNuevo = 'BACKUPVENTAS\\';

            //Si no existe creo la carpeta
            is_dir(getcwd() . "\\" . $directorioNuevo) ? : mkdir($directorioNuevo);

            if(copy($directorio.$nombreArchivo, $directorioNuevo.$nombreArchivo)) {
                unlink($directorio.$nombreArchivo);
                echo "La foto se guardó correctamente.\n";
                //Elimino la venta
                unset($arrayVentas[$indexVenta]);
            }else{
                echo "La foto no pudo guardarse.\n";
            }

        }else {
            echo "No se encontró ninguna venta con ese número de pedido";
        }  
    } else {
        echo "Ingrese bien el numero de pedido para poder eliminar la venta";
    }
    return $arrayVentas;
}

function generarNombreArchivo($ventas, $indexVenta) {
  return $ventas[$indexVenta]->_saborHelado . '_' . $ventas[$indexVenta]->_tipoHelado . '_' . $ventas[$indexVenta]->_vasoHelado . '_' . explode('@', $ventas[$indexVenta]->_emailUsuario)[0] . '_' . $ventas[$indexVenta]->_fechaPedido . '.jpg';
}
?>