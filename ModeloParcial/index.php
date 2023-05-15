<?php
// DESDE EL INDEX RECIBIMOS DESDE POST

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        //Me fijo que funcion me vino por POST
        switch ($_POST['funcion']){
            case "consultarPizza":
                include_once "./PizzaConsultar.php";
            break;   
            case "nuevaVenta":
                include_once "./AltaVenta.php";
            break;
            case "nuevaPizza":
                include_once "./PizzaCarga.php";
            break;
            case 'consultarVentas':
                include_once "ConsultasVentas.php";
            break;
            default:
             echo "Ingrese bien la funcion";
            break; 
        }
    break;
    case 'GET':
    break;
    case 'DELETE':
        include_once "borrarVenta.php";
        break;
    case 'PUT':
        include_once "ModificarVenta.php";
    break;
}
?>