<?php
function LeerJSON(string $nombreArchivo)
{
    $referenciaArchivo = fopen($nombreArchivo, "a+");

    if ($referenciaArchivo) {
        $textoArchivo = fgets($referenciaArchivo);
        json_decode($textoArchivo, false) != null ? $decode = json_decode($textoArchivo, false) : $decode = array();
    }
    fclose($referenciaArchivo);
    return $decode;
}

function GuardarJSON(array $datos, string $nombreArchivo)
{
    $referenciaArchivo = fopen($nombreArchivo, "w+");
    if ($referenciaArchivo) {
        fwrite($referenciaArchivo, json_encode($datos));
    }
    return fclose($referenciaArchivo);
}
?>