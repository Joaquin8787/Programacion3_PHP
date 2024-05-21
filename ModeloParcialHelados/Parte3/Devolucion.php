<?php
include_once "ManejoArchivos.php";

class Devolucion
{
    public int $_id;
    public string $_causa;
    public int $_numeroPedido;
    public int $_idCupon;

    public function __construct(int $id,string $causa, int $numeroPedido, int $idCupon)
    {
        $this->_id = $id;
        $this->_causa = $causa;
        $this->_numeroPedido = $numeroPedido;
        $this->_idCupon = $idCupon;
    }

    public static function BuscarDevolucion(array $arrayDevoluciones, $numeroPedido)
    {
        $indice = 0;
        foreach($arrayDevoluciones as $devolucion){
            if($devolucion->_numeroPedido == $numeroPedido){
                return $indice;
                break;
            }
            $indice++;
        }
        return -1;
    }

    public static function GuardarImagenClienteEnojado($venta)
    {
        $retorno = false;
        $path = 'ImagenesClientesEnojados';

        // Comprobar si la carpeta de destino existe, si no, crearla
        if (!file_exists($path)) {
            mkdir($path);
        }
        //Uso el explode para separar el mail
        $emailSeparado = explode("@", $venta->_emailUsuario); 
        //Guardo con tipo, sabor, mail y fecha      
        $archivo = $venta->_saborHelado . '_' . $venta->_tipoHelado . '_' .$venta->_vasoHelado . '_' . $emailSeparado[0] . '_' . $venta->_fechaPedido;
        //Obtengo la ubicacion temporal donde se subio el archivo
        $tmpName = $_FILES["imagen"]["tmp_name"];
        $destino = $path . "/" . $archivo . ".jpg";
        
        if (move_uploaded_file($tmpName, $destino)) {
            echo "Se pudo guardar la imagen del cliente enojado correctamente.\n";
            $retorno = true;
        }else{
            echo "Hubo algun problema al guardar la imagen del cliente enojado.\n";
        }
        return $retorno;
    }
}