<?php
/* Se ingresa Sabor y Tipo, si coincide con algún registro del archivo
heladeria.json, retornar “existe”. 
De lo contrario informar si no existe el tipo o el nombre*/
include_once "./Pizza.php";
include_once "./Funciones.php";
//Leo el archivo con las pizzas
$_arrayPizzas = LeerJSON("Pizza.json");

//Sabor y tipo por el cual estan buscando
$_sabor = $_POST['sabor'];
$_tipo = $_POST['tipo'];

//Verifico si los parametros no estan vacios
if (!empty(trim($_sabor)) && !empty(trim($_tipo))){
    $pizza = new Pizza($_sabor,$_tipo);

    if (Funciones::BuscarElemento($_arrayPizzas, $pizza) != -1){
        echo "Si hay!!!\n";
    }
    else{
        echo "La pizza buscada no existe...\n";
    }
}else{
    echo "Ingrese bien los datos...\n";
}

?>