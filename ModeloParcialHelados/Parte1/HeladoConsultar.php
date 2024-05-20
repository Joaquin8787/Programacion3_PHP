<?php
include_once "./Helado.php";
include_once "./Funciones.php";

//Leo el archivo con los Helados
$_arrayHelados = LeerJSON("heladeria.json");

//Sabor y tipo por el cual estan buscando que vienen por POST
$_sabor = $_POST['sabor'];
$_tipo = $_POST['tipo'];

//Verifico si los parametros no estan vacios
if (!empty(trim($_sabor)) && !empty(trim($_tipo))){
    $helado = new Helado($_sabor,$_tipo);

    if (Funciones::BuscarElemento($_arrayHelados, $helado) != -1){
        echo "Si existe!!!\n";
    }
    else{
        echo "El helado buscado no existe\n";
    }
}else{
    echo "Ingrese bien los datos\n";
}

?>