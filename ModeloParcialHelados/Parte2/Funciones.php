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
    public static function GenerarNuevoValor(array $array,$min,$max,string $tipoValor){
        $numero = random_int($min, $max);
        while (array_search($numero, array_column($array,$tipoValor)) != false) {
            $numero = rand($min, $max);
        }
        return $numero;
    }
}
?>