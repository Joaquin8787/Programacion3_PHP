<?php
include_once "./Ventas.php";
include_once "./Helado.php";
include_once "./Funciones.php";
include_once "./Cupon.php";

$_arrayHelados = LeerJSON("heladeria.json");
$_arrayVentas = LeerJSON("Ventas.json");
$_arrayCupones = LeerJSON("Cupones.json");
$_arrayVentas = GenerarVenta($_arrayHelados,$_arrayVentas,$_arrayCupones);
GuardarJSON($_arrayVentas,"Ventas.json");

function GenerarVenta(array $arrayHelados,array $arrayVentas,array $_arrayCupones){

    //Instancio la Helado que pidieron
    $helado = new Helado($_POST['sabor'],$_POST['tipo']);
    //stock que me pidieron
    $cantidad = (int)$_POST['stock'];
    //cupon ingresado
    $id_cupon = (int)$_POST['id_cupon'];

    //Busco si existe la Helado del sabor y tipo elegidos
    $indexHelado = Funciones::BuscarElemento($arrayHelados, $helado);

    if($indexHelado != -1){
        //Me traigo la Helado del array de Helados
        $heladoEncontrado = $arrayHelados[$indexHelado];
        //Verifico que haya stock
        if($heladoEncontrado->_stock > 0){
            //Si la stock que hay es mayor o igual a lo que me pidieron
            if($heladoEncontrado->_stock < $cantidad){
                $stockHelado = (int)$heladoEncontrado->_stock;
                echo "No tenemos la cantidad que solicito solo contamos con este stock de lo solicitado: ". $stockHelado;
                $stockNuevo  = (int)$heladoEncontrado->_stock - $stockHelado;
            }
            else{
                $stockNuevo  = (int)$heladoEncontrado->_stock - $cantidad;
            }


            $precio = (float)$heladoEncontrado->_precio;
            //Instancio una nueva venta
            $venta = new Venta($_POST['emailUsuario'],$heladoEncontrado->_sabor,$heladoEncontrado->_tipo,$heladoEncontrado->_vaso,$cantidad,$precio);
            
            $indexCupon = Cupon::BuscarCuponActivo($_arrayCupones, $id_cupon);

            // Aplico el descuento del cupon
            if($indexCupon != -1) {
                $descuento = $_arrayCupones[$indexCupon]->_porcentajeDescuento / 100;
                $precioConDescuento = $venta->_precioPedido * (1 - $descuento);
                $venta->_precioPedido = $precioConDescuento;
                $_arrayCupones[$indexCupon]->_estado = "usado";
                GuardarJSON($_arrayCupones, "Cupones.json");
            } else {
                echo "El cupon ingresado no es inválido y no fue aplicado...\n";
            }

            if($venta->GuardarImagenVenta()){
                //Agrego la venta al array de ventas
                array_push($arrayVentas,$venta);
                //Resto el stock
                $arrayHelados[$indexHelado]->_stock = $stockNuevo;
                GuardarJSON($arrayHelados, "heladeria.json");
                echo "Se realizo la venta correctamente!!!";
            }
            else{
                echo "No se pudo realizar la venta";
            }
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