<?php
include_once "Venta.php";
include_once "Devolucion.php";
include_once "Cupon.php";

$_arrayVentas = LeerDatosJSON("Ventas.json");
$_arrayCupones = LeerDatosJSON("Cupones.json");
$_arrayDevoluciones = LeerDatosJSON("Devoluciones.json");

$numeroPedido = empty($_POST['numeroPedido']) ? 0 : (int)$_POST['numeroPedido'];

//Busco la venta que requiere devolucion
$indexVenta = Venta::BuscarVenta($_arrayVentas, $numeroPedido);

//Busco la devolucion correspondiente al numero del pedido para ver si ya se hizo
$indexDevolucion = Devolucion::BuscarDevolucion($_arrayDevoluciones, $numeroPedido);

if ($indexVenta != -1) {
	if ($indexDevolucion == -1) {
		$retorno = Devolucion::GuardarImagenClienteEnojado($_arrayVentas[$indexVenta]);
        if($retorno){
            //Genero el cupon de descuento
            $cupon = new CuponDeDescuento($_arrayDevoluciones[$indexDevolucion]->_id, $_POST['causa']);
            array_push($_arrayCupones, $cupon);
            GuardarDatosJSON($_arrayCupones, "cupones.json");

            //Dejo constancia de la devolucion
            $devolucion = new Devolucion($cupon->_causa, $numPedido, $cupon->_id);
            array_push($_arrayDevoluciones, $devolucion);
            GuardarDatosJSON($_arrayDevoluciones, "devoluciones.json");

            echo "Queja anotada! El número de su cupón es $cupon->_id";
        }
        else{
            echo "No se pudo generar el cupon de descuento...";
        }		
	} else {
		echo "Ya se realizó una devolución por esta venta!\n";
	}
} else {
    echo "No existe una venta activa con número de pedido N°$numeroPedido.\n";
}
?>