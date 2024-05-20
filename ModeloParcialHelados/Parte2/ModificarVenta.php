<?php
include_once "Ventas.php";
include_once "Helado.php";

$_arrayVentas = LeerJSON("Ventas.json");
$_arrayHelados = LeerJSON("heladeria.json");
$_arrayVentas = ModificarVenta($_arrayVentas, $_arrayHelados);
GuardarJSON($_arrayVentas, "Ventas.json");

function ModificarVenta(array $arrayVentas, array $arrayHelados){
    // Obtener datos de la solicitud PUT
    $datos = json_decode(file_get_contents("php://input"));
    
    // Verificar que se hayan recibido todos los datos necesario
    if(isset($datos->numeroPedido, $datos->email, $datos->tipo, $datos->vaso, $datos->cantidad)){
        // Busco la venta correspondiente al número de pedido recibido
        $indexVenta = Venta::BuscarVenta($arrayVentas, $datos->numeroPedido);

        if($indexVenta != -1){
            $ventaEncontrada = $arrayVentas[$indexVenta];
            
            $ventaEncontrada->_emailUsuario = $datos->email;
            $ventaEncontrada->_tipoHelado = $datos->tipo;
            $ventaEncontrada->_vasoHelado = $datos->vaso;
            $ventaEncontrada->_stockHelado = $datos->cantidad;

            echo "Se realizo la modificacion con exito!!!";
        }else{
            echo "No se encontró ninguna venta con ese número de pedido";
        }  
    } else {
        // Retornar mensaje de error
        echo "Faltan datos para modificar la venta";
    }
    return $arrayVentas;
}
?>