<?php
/*
 ModificarVenta.php(por PUT), debe recibir el número de pedido, el email del usuario, el sabor,tipo y
 cantidad, si existe se modifica , de lo contrario informar.
*/
include_once "Ventas.php";
include_once "Pizza.php";

$_arrayVentas = LeerJSON("Ventas.json");
$_arrayPizzas = LeerJSON("Pizza.json");
$_arrayVentas = ModificarVenta($_arrayVentas, $_arrayPizzas);
GuardarJSON($_arrayVentas, "Ventas.json");

function ModificarVenta(array $arrayVentas, array $arrayPizzas){
    // Obtener datos de la solicitud PUT
    $datos = json_decode(file_get_contents("php://input"));
    
    // Verificar que se hayan recibido todos los datos necesario
    if(isset($datos->numeroPedido, $datos->emailUsuario, $datos->sabor, $datos->tipo, $datos->cantidad)){
        // Busco la venta correspondiente al número de pedido recibido
        $indexVenta = Venta::BuscarVenta($arrayVentas, $datos->numeroPedido);

        if($indexVenta != -1){
            $ventaEncontrada = $arrayVentas[$indexVenta];
            
            $ventaEncontrada->_emailUsuario = $datos->emailUsuario;
            $ventaEncontrada->_saborPizza = $datos->sabor;
            $ventaEncontrada->_tipoPizza = $datos->tipo;
            $ventaEncontrada->_cantidadPizza = $datos->cantidad;

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