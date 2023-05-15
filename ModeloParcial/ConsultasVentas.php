<?php
/*
a- la cantidad de pizzas vendidas
b- el listado de ventas entre dos fechas ordenado por sabor.
c- el listado de ventas de un usuario ingresado
d- el listado de ventas de un sabor ingresado
*/
include_once "Ventas.php";

$_arrayVentas = LeerJSON("Ventas.json");

//a)
MostrarCantidadPizzas($_arrayVentas);
echo "*********************************************************";

//b)
$fechaDesde = DateTime::createFromFormat('d-m-Y', '01-05-2023');
$fechaHasta = DateTime::createFromFormat('d-m-Y', '13-05-2023');
$ventasFiltradasPorFecha = ventasPorFechaYSabor($_arrayVentas, $fechaDesde, $fechaHasta);

echo "\n * POR FECHA *";
MostrarVentas($ventasFiltradasPorFecha);
echo "\n*********************************************************";
//c)
$ventasFiltradaPorUsuario = ventasPorUsuario($_arrayVentas, "paco@hotmail.com");

echo "\n * POR USUARIO *";
MostrarVentas($ventasFiltradaPorUsuario);
echo "\n*********************************************************";
//d)
$ventasFiltradaPorSabor = ventasPorSabor($_arrayVentas, "muzza");
echo "\n * POR SABOR *";
MostrarVentas($ventasFiltradaPorSabor);
echo "\n*********************************************************";
#region Funciones
//a- la cantidad de pizzas vendidas
function MostrarCantidadPizzas($arrayVentas){
    $cantidadPizzasVendidas = 0;
    foreach ($arrayVentas as $venta) {
        $cantidadPizzasVendidas += $venta->_cantidadPizza;
    }
    echo "La cantidad de pizzas vendidas es: " . $cantidadPizzasVendidas . "\n";    
}

//b- el listado de ventas entre dos fechas ordenado por sabor.
function ventasPorFechaYSabor($arrayVentas, $fechaDesde, $fechaHasta){
    $ventasFiltradas = array_filter($arrayVentas, function($venta) use ($fechaDesde, $fechaHasta){
        $fechaVenta = DateTime::createFromFormat('d-m-Y', $venta->_fechaPedido);
        return $fechaVenta >= $fechaDesde && $fechaVenta <= $fechaHasta;
    });

    usort($ventasFiltradas, function($venta1, $venta2){
        return strcmp($venta1->_saborPizza, $venta2->_saborPizza);
    });
    return $ventasFiltradas;
}

//c- el listado de ventas de un usuario ingresado
function ventasPorUsuario($arrayVentas, $emailUsuario){
    $ventasFiltradas = array_filter($arrayVentas, function($venta) use ($emailUsuario) {
      return $venta->_emailUsuario === $emailUsuario;
    });
    return $ventasFiltradas;
}

//d- el listado de ventas de un sabor ingresado
function ventasPorSabor($arrayVentas, $saborPizza){
    $ventasFiltradas = array_filter($arrayVentas, function($venta) use ($saborPizza) {
      return $venta->_saborPizza === $saborPizza;
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
    $retorno .= "Sabor de Pizza: $venta->_saborPizza \n";
    $retorno .= "Tipo de Pizza: $venta->_tipoPizza \n";
    $retorno .= "Cantidad: $venta->_cantidadPizza \n";
    $retorno .="Fecha del pedido: $venta->_fechaPedido \n";
    return $retorno;
}
#endregion
?>