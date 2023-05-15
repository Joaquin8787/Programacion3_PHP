<?php
include_once "ManejoArchivos.php";

class Devolucion
{
    public int $_id;
    public string $_causa;
    public int $_numeroPedido;
    public int $_idCupon;

    public function __construct(string $causa, int $numeroDePedido, int $idCupon)
    {
        $this->_id = count(LeerDatosJSON("devoluciones.json")) + 1;
        $this->_causa = $causa;
        $this->_numeroDePedido = $numeroPedido;
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
        return $retorno;
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
        $archivo = $this->_tipoPizza . '_' . $this->_saborPizza . '_' . $emailSeparado[0] . '_' . $this->_fechaPedido;
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