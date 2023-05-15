<?php
/*
AltaVenta.php: (por POST)se recibe el email del usuario y el sabor,tipo y cantidad ,si el ítem existe en
Pizza.json, y hay stock guardar en la base de datos( con la fecha, número de pedido y id autoincremental ) y se
debe descontar la cantidad vendida del stock .
*/
include_once "./Ventas.php";
include_once "./Pizza.php";

$_arrayPizzas = LeerJSON("Pizza.json");
$_arrayVentas = LeerJSON("Ventas.json");
$_arrayVentas = GenerarVenta($_arrayPizzas,$_arrayVentas);
GuardarJSON($_arrayVentas,"Ventas.json");

function GenerarVenta(array $arrayPizzas,array $arrayVentas){
    //Instancio la pizza que pidieron
    $pizza = new Pizza($_POST['sabor'],$_POST['tipo']);
    //Cantidad que me pidieron
    $cantidad = (int)$_POST['cantidad'];

    //Busco si existe la pizza del sabor y tipo elegidos
    $indexPizza = Funciones::BuscarElemento($arrayPizzas, $pizza);

    if($indexPizza != -1){
        //Me traigo la pizza del array de pizzas
        $pizzaEncontrada = $arrayPizzas[$indexPizza];
        //Verifico que haya stock
        if($pizzaEncontrada->_cantidad > 0){
            //Si la cantidad que hay es mayor o igual a lo que me pidieron
            if($pizzaEncontrada->_cantidad < $cantidad){
                $cantidad = (int)$pizzaEncontrada->_cantidad;
                echo "No tenemos la cantidad que solicito, le ofrecemos solo la cantidad con la que contamos que es: ". $cantidad ;
            }
            //Creo stock
            $stock  = (int)$pizzaEncontrada->_cantidad - $cantidad;
            //Instancio una nueva venta
            $venta = new Venta($_POST['emailUsuario'],$pizzaEncontrada->_sabor,$pizzaEncontrada->_tipo,$cantidad,$_POST['fechaPedido']);
            
            //Agrego la venta al array de ventas
            array_push($arrayVentas,$venta);
            $venta->GuardarImagenVenta();

            //Resto el stock
            $arrayPizzas[$indexPizza]->_cantidad = $stock;
            GuardarJSON($arrayPizzas, "Pizza.json");
        }
        else{
            echo "No hay stock de la pizza seleccionada, elija otra pizza porfavor...";
        }
    }
    else{
        echo "La pizza escogida no existe...";
    }
    return $arrayVentas;
}

?>