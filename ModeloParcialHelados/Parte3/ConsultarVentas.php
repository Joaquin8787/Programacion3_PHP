<?php
include_once "Ventas.php";

$_arrayVentas = LeerJSON("Ventas.json");
$_fecha = $_GET['fecha'];
$_usuario = $_GET['usuario'];
$_sabor = $_GET['sabor'];
$_vaso = $_GET['vaso'];

//a)
MostrarCantidadHeladosPorFecha($_arrayVentas,$_fecha);

//b)
echo "*********************************************************";
$ventasFiltradaPorUsuario = ventasPorUsuario($_arrayVentas, $_usuario);
echo "\n * POR USUARIO: $_usuario *";
MostrarVentas($ventasFiltradaPorUsuario);

//c)
echo "\n*********************************************************";
$fechaDesde = DateTime::createFromFormat('d-m-Y', '01-05-2024');
$fechaHasta = DateTime::createFromFormat('d-m-Y', '20-05-2024');
$ventasFiltradasPorFecha = ventasPorFechaYSabor($_arrayVentas, $fechaDesde, $fechaHasta);
echo "\n * POR FECHA *: Desde {$fechaDesde->format('d-m-Y')} hasta {$fechaHasta->format('d-m-Y')}";
MostrarVentas($ventasFiltradasPorFecha);

//d)
$ventasFiltradaPorSabor = ventasPorSabor($_arrayVentas, $_sabor);
echo "\n * POR SABOR *";
MostrarVentas($ventasFiltradaPorSabor);
echo "\n*********************************************************";

//e)
$ventasFiltradaPorVaso = ventasPorVaso($_arrayVentas, $_vaso);
echo "\n * POR VASO *";
MostrarVentas($ventasFiltradaPorVaso);
echo "\n*********************************************************";

// FUNCIONES

//a- la cantidad de Helados vendidos en una fecha en especifico
function MostrarCantidadHeladosPorFecha($arrayVentas,$fecha = null){
    $cantidadHeladosVendidos = 0;
    if (!$fecha) {
        $fecha = date('d-m-Y', strtotime('-1 day')); // Formato 'd-m-Y'
    }
    foreach ($arrayVentas as $venta) {
        if($fecha == $venta->_fechaPedido){
            $cantidadHeladosVendidos += $venta->_stockHelado;
        }
    }
    echo "La cantidad de Helados vendidos es: " . $cantidadHeladosVendidos . "\n";    
}

//b- el listado de ventas entre dos fechas ordenado por nombre.
function ventasPorFechaYSabor($arrayVentas, $fechaDesde, $fechaHasta){

    $ventasFiltradas = array_filter($arrayVentas, function($venta) use ($fechaDesde, $fechaHasta){
        $fechaVenta = DateTime::createFromFormat('d-m-Y', $venta->_fechaPedido);
        return $fechaVenta >= $fechaDesde && $fechaVenta <= $fechaHasta;
    });

    usort($ventasFiltradas, function($venta1, $venta2){
        return strcmp($venta1->_saborHelado, $venta2->_saborHelado);
    });
    return $ventasFiltradas;
}

//c- el listado de ventas de un usuario ingresado
function ventasPorUsuario($arrayVentas, $emailUsuario){
    $ventasFiltradas = array_filter($arrayVentas, function($venta) use ($emailUsuario) {
      return $venta->_emailUsuario == $emailUsuario;
    });
    return $ventasFiltradas;
}

//d- el listado de ventas de un sabor ingresado
function ventasPorSabor($arrayVentas, $saborHelado){
    $ventasFiltradas = array_filter($arrayVentas, function($venta) use ($saborHelado) {
      return $venta->_saborHelado === $saborHelado;
    });
    return $ventasFiltradas;
}

//d- el listado de ventas de un sabor ingresado
function ventasPorVaso($arrayVentas, $vasoHelado){
    $ventasFiltradas = array_filter($arrayVentas, function($venta) use ($vasoHelado) {
      return $venta->_vasoHelado === $vasoHelado;
    });
    return $ventasFiltradas;
}

//Funciones para mostrar ventas
function MostrarVentas($arrayVentas){
    foreach ($arrayVentas as $venta) {
    echo "\n" . MostrarVenta($venta);
    echo "----------------------------";
    }
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