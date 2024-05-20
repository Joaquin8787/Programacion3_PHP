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
            default:
             echo "Ingrese bien la funcion";
            break; 
        }
    break;
}
?>