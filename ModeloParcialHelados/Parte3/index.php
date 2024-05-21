<?php
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        switch ($_POST['funcion']){
            case "nuevoHelado":
                include_once "./HeladeriaAlta.php";
            break;
            case "consultarHelado":
                include_once "./HeladoConsultar.php";
            break;
            case 'nuevaVenta':
                include_once "AltaVenta.php";
            break;
            case 'devolverHelado':
                include_once "DevolverHelado.php";
            break;
            default:
             echo "Ingrese bien la funcion";
            break; 
        }
    break;
    case 'GET':
        switch ($_GET['funcion']) {
            case 'consultarVentas':
                include_once "ConsultarVentas.php";
            break;
            default:
            echo "Ingrese bien la funcion";
            break;
        }
    break;
    case 'PUT':
        include_once "ModificarVenta.php";
    break;
    case 'DELETE':
        include_once "borrarVenta.php";
    break;
}
?>