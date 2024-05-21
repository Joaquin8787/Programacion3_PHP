<?php
include_once "./Helado.php";
include_once "./Funciones.php";

$_arrayHelados= LeerJSON("heladeria.json");
$_arrayHelados = AgregarHelado($_arrayHelados);
GuardarJSON($_arrayHelados, "heladeria.json");

function AgregarHelado(array $arrayHelados){
    //Instancio una nueva Helado
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $helado = new Helado($_POST['sabor'],$_POST['tipo'],$precio,$_POST['vaso'],$stock);
    
    //Me fijo si la Helado ya existe
    $indexHelado = Funciones::BuscarElemento($arrayHelados, $helado);
    
    if($indexHelado != -1){
        //Busco la Helado en el array de Helados
        $heladoEncontrado = $arrayHelados[$indexHelado];
        //Actualizo el precio
        $heladoEncontrado->_precio = $helado->_precio;
        //Actualizo el stock
        $heladoEncontrado->_stock += $helado->_stock;
        echo "Stock actualizado!:\n";
        echo $heladoEncontrado->_stock . ' helados sabor ' . $heladoEncontrado->_sabor . ' de tipo ' . $arrayHelados[$indexHelado]->_tipo .  ' de vaso ' . $arrayHelados[$indexHelado]->_vaso .' por $' . $arrayHelados[$indexHelado]->_precio . "\n";
        //$auxArray[$indexHelado] =  $heladoEncontrado;    
    }
    else{
        if($helado->GuardarImagenHelado()){
            //Agrego la Helado al final de array
            array_push($arrayHelados, $helado);
            echo "Helado agregado!!:\n";
            echo $helado->_stock . ' helados sabor ' . $helado->_sabor . ' de tipo' . $helado->_tipo . ' de vaso ' . $helado->_vaso .' por $' . $helado->_precio . "\n";
        }
    }
    return $arrayHelados;
}
?>