<?php
include_once "./Ventas.php";
include_once "./Helado.php";
include_once "./Funciones.php";

$_arrayHelados = LeerJSON("heladeria.json");
$_arrayVentas = LeerJSON("Ventas.json");
$_arrayVentas = GenerarVenta($_arrayHelados,$_arrayVentas);
GuardarJSON($_arrayVentas,"Ventas.json");

function GenerarVenta(array $arrayHelados,array $arrayVentas){
    //Instancio la Helado que pidieron
    $helado = new Helado($_POST['sabor'],$_POST['tipo']);
    //stock que me pidieron
    $stock = (int)$_POST['stock'];

    //Busco si existe la Helado del sabor y tipo elegidos
    $indexHelado = Funciones::BuscarElemento($arrayHelados, $helado);

    if($indexHelado != -1){
        //Me traigo la Helado del array de Helados
        $heladoEncontrado = $arrayHelados[$indexHelado];
        //Verifico que haya stock
        if($heladoEncontrado->_stock > 0){
            //Si la stock que hay es mayor o igual a lo que me pidieron
            if($heladoEncontrado->_stock < $stock){
                $stock = (int)$heladoEncontrado->_stock;
                echo "No tenemos la cantidad que solicito solo contamos con este stock de lo solicitado: ". $stock;
            }

            //Creo stock
            $stock  = (int)$heladoEncontrado->_stock - $stock;
            //Instancio una nueva venta
            $venta = new Venta($_POST['emailUsuario'],$heladoEncontrado->_sabor,$heladoEncontrado->_tipo,$heladoEncontrado->_vaso,$stock);
            
            //Agrego la venta al array de ventas
            array_push($arrayVentas,$venta);
            $venta->GuardarImagenVenta();

            //Resto el stock
            $arrayHelados[$indexHelado]->_stock = $stock;
            GuardarJSON($arrayHelados, "heladeria.json");
        }
        else{
            echo "No hay stock de la helado seleccionado, elija otro helado";
        }
    }
    else{
        echo "La helado escogida no existe...";
    }
    return $arrayVentas;
}

?>