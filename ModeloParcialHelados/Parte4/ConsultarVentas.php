<?php
include_once "Ventas.php";

$_arrayVentas = LeerJSON("Ventas.json");
$_arrayCupones = LeerJSON("Cupones.json");
$_arrayDevoluciones = LeerJSON("Devoluciones.json");

$_fecha = $_GET['fecha'];
$_usuario = $_GET['usuario'];
$_sabor = $_GET['sabor'];
$_vaso = $_GET['vaso'];

// //a)
// MostrarCantidadHeladosPorFecha($_arrayVentas,$_fecha);

// //b)
// echo "*********************************************************";
// $ventasFiltradaPorUsuario = arrayFiltradoPorAtributo($_arrayVentas,"_emailUsuario",$_usuario);
// echo "\n * POR USUARIO: $_usuario *";
// Mostrar($ventasFiltradaPorUsuario,"MostrarVentas");

// //c)
// echo "\n*********************************************************";
// $fechaDesde = DateTime::createFromFormat('d-m-Y', '01-05-2024');
// $fechaHasta = DateTime::createFromFormat('d-m-Y', '20-05-2024');
// $ventasFiltradasPorFecha = ventasPorFechaYSabor($_arrayVentas, $fechaDesde, $fechaHasta);
// echo "\n * POR FECHA *: Desde {$fechaDesde->format('d-m-Y')} hasta {$fechaHasta->format('d-m-Y')}";
// MostrarVentas($ventasFiltradasPorFecha);

// //d)
// $ventasFiltradaPorSabor = arrayFiltradoPorAtributo($_arrayVentas,"_sabor",$_sabor);
// echo "\n * POR SABOR *";
// Mostrar($ventasFiltradaPorSabor,"MostrarVentas");
// echo "\n*********************************************************";

// //e)
// $ventasFiltradaPorVaso = arrayFiltradoPorAtributo($_arrayVentas,_vasoHelado, $_vaso);
// echo "\n * POR VASO *";
// Mostrar($ventasFiltradaPorVaso,"MostrarVentas");
// echo "\n*********************************************************";

//a)
echo "\n * DEVOLUCIONES CON CUPONES *";
MostrarCoincidencias($_arrayDevoluciones, $_arrayCupones,"_idCupon","_id");
echo "\n*********************************************************";

//b)
$cuponesFiltradoPorEstado= arrayFiltradoPorAtributo($_arrayCupones,"_estado", "usado");
echo "\n * CUPONES Y SU ESTADO*";
Mostrar($cuponesFiltradoPorEstado,"MostrarCupon");
echo "\n*********************************************************";

//c)
$ventasFiltradaPorVaso = ventasPorVaso($_arrayVentas, $_vaso);
echo "\n * DEVOLUCIONES, CUPONES Y ESTADO*";
MostrarVentas($ventasFiltradaPorVaso);
echo "\n*********************************************************";


// FUNCIONES

//a- la cantidad de Helados vendidos en una fecha en especifico
function MostrarCantidadAtributoPorFecha($array,$fecha = null, $atributo){
    $cantidad = 0;
    if (!$fecha) {
        $fecha = date('d-m-Y', strtotime('-1 day')); // Formato 'd-m-Y'
    }
    foreach ($array as $elemento) {
        if($fecha == $elemento->_fechaPedido){
            $cantidad += $elemento->$atributo;
        }
    }
    echo "La cantidad de Helados vendidos es: " . $cantidad . "\n";    
}

//b- el listado de ventas entre dos fechas ordenado por nombre.
function FiltrarPorFechaOrdenadoPorAtributo($array, $fechaDesde, $fechaHasta,$atributo){

    $arrayFiltrado = array_filter($array, function($elemento) use ($fechaDesde, $fechaHasta){
        $fechaVenta = DateTime::createFromFormat('d-m-Y', $elemento->_fechaPedido);
        return $fechaVenta >= $fechaDesde && $fechaVenta <= $fechaHasta;
    });

    usort($arrayFiltrado, function($elemento1, $elemento2){
        return strcmp($elemento1->_saborHelado, $elemento2->$atributo);
    });
    return $arrayFiltrado;
}

//c- el listado de ventas de un usuario ingresado
function arrayFiltradoPorAtributo($array, $atributo,$valor){
    $arrayFiltrado = array_filter($array, function($elemento) use ($atributo, $valor) {
      return $elemento->$atributo == $valor;
    });
    return $arrayFiltrado;
}

function MostrarCoincidencias($array1, $array2,$atributo,$atributo2) {

    foreach ($array1 as $elemento) {
        foreach($array2 as $elemento2){
            if($elemento->$atributo == $elemento2->$atributo2){
                echo MostrarDevolucion($elemento,$elemento2);
            }
        }
    }
}

//Funciones para mostrar ventas
function Mostrar($array,$funcion){
    foreach ($array as $elemento) {
    echo "\n" . call_user_func($funcion,$elemento);
    echo "----------------------------";
    }
}

function MostrarCupon($cupon){
    $retorno = "\n";
    $retorno .= "ID: $cupon->_id \n";
    $retorno .= "ID DEVOLUCION: $cupon->_idDevolucion \n";
    $retorno .= "CAUSA: $cupon->_causa \n";
    $retorno .= "DESCUENTO: %$cupon->_porcentajeDescuento \n";
    $retorno .= "ESTADO: $cupon->_estado \n";
    return $retorno;
}
function MostrarDevolucion($devolucion,$cupon){
    $retorno = "\n";
    $retorno .= "ID DEVOLUCION: $devolucion->_id \n";
    $retorno .= "CAUSA DEVOLUCION: $devolucion->_causa \n";
    $retorno .= "NUMERO PEDIDO: $devolucion->_numeroPedido \n";
    $retorno .= "ID CUPON: $devolucion->_idCupon \n";
    $retorno .= "\nCUPON RELACIONADO:";
    $retorno .= MostrarCupon($cupon);
    $retorno .= "----------------------------------------";
    
    return $retorno;
}
function MostrarVenta($venta){
    $retorno = "";
    $retorno .= "Numero Pedido: $venta->_numeroPedido \n";
    $retorno .= "Email Usuario: $venta->_emailUsuario \n";
    $retorno .= "Sabor de Helado: $venta->_saborHelado \n";
    $retorno .= "Tipo de Helado: $venta->_tipoHelado \n";
    $retorno .= "Cantidad: $venta->_stockHelado \n";
    $retorno .= "Vaso: $venta->_vasoHelado \n";
    $retorno .="Fecha del pedido: $venta->_fechaPedido \n";
    return $retorno;
}
#endregion
?>