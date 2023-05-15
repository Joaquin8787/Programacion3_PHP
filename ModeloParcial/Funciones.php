<?php
class Funciones{
    public static function BuscarElemento(array $array, $valor){
        $indice = 0;
        foreach($array as $elemento){
            if($valor->EsIgual($elemento)){
                return $indice;
            }
            $indice++;
        }
        return -1;
    }
}
?>