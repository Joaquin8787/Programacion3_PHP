<?php
include_once "./Pizza.php";

$_arrayPizzas= LeerJSON("Pizza.json");
$_arrayPizzas = AddPizza($_arrayPizzas);
GuardarJSON($_arrayPizzas, "Pizza.json");

function AddPizza(array $arrayPizzas){
    //Instancio una nueva pizza
    $precio = (float)$_POST['precio'];
    $cantidad = (int)$_POST['cantidad'];
    $pizza = new Pizza($_POST['sabor'],$_POST['tipo'],$precio,$cantidad);
    //Me fijo si la pizza ya existe
    $indexPizza = Funciones::BuscarElemento($arrayPizzas, $pizza);
    
    if($indexPizza != -1){
        //Busco la pizza en el array de pizzas
        $pizzaEncontrada = $arrayPizzas[$indexPizza];
        //Actualizo el precio
        $pizzaEncontrada->_precio = $pizza->_precio;
        //Actualizo el stock
        $pizzaEncontrada->_cantidad += $pizza->_cantidad;
        echo "Stock actualizado con exito!:\n";
        echo $pizzaEncontrada->_cantidad . ' pizzas sabor ' . $pizzaEncontrada->_sabor . ' tipo ' . $arrayPizzas[$indexPizza]->_tipo . ' por $' . $arrayPizzas[$indexPizza]->_precio . "\n";
        //$auxArray[$indexPizza] =  $pizzaEncontrada;    
    }
    else{
        //Agrego la pizza al final de array
        array_push($arrayPizzas, $pizza);
        echo "Pizza agregada con exito!!:\n";
        echo $pizza->_cantidad . ' pizzas sabor ' . $pizza->_sabor . ' de ' . $pizza->_tipo . ' por $' . $pizza->_precio . "\n";
        $pizza->GuardarImagenPizza();
    }
    return $arrayPizzas;
}
?>