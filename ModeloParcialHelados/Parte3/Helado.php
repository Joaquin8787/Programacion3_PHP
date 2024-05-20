<?php
include_once "./ManejoArchivos.php";

class Helado{
    public int $_id;
    public string $_sabor;
    public float $_precio;
    public string $_tipo;
    public string $_vaso;
    public int $_stock;

    //SETTERS
    public function setID(){
        //id autoincremental
        $this->_id = count(LeerJSON("heladeria.json"))+1;
    }

    public function setSabor(string $sabor){
        $s = str_replace(' ', '_', $sabor);
        $this->_sabor = empty(trim($s)) ? "menta granizada" : strtolower($s);
    }

    public function setPrecio(float $precio){
        $precio <= 0 ? $this->_precio = 175 : $this->_precio = $precio;
    }

    public function setTipo(string $tipo){
        $tipoLower = empty(trim($tipo)) ? "_" : strtolower($tipo);
        if($tipoLower == "agua" || $tipoLower == "crema"){
            $this->_tipo = $tipoLower;
        }else{
            random_int(0,1) == 0 ? $this->_tipo = "agua" : $this->_tipo = "crema";
        }
    }

    public function setVaso(string $vaso){
        $vasoLower = empty(trim($vaso)) ? "_" : strtolower($vaso);
        if($vasoLower == "cucurucho" || $vasoLower == "plastico"){
            $this->_vaso = $vasoLower;
        }else{
            random_int(0,1) == 0 ? $this->_vaso = "cucurucho" : $this->_vaso = "plastico";
        }
    }

    public function setStock(int $stock){
        $stock <= 0 ? $this->_stock = 1 : $this->_stock = $stock;
    }

    //CONSTRUCTOR
    public function __construct(string $sabor, string $tipo, float $precio = 0,string $vaso = "", int $stock = 0){
        $this->setID();
        $this->setSabor($sabor);
        $this->setPrecio($precio);
        $this->setTipo($tipo);
        $this->setVaso($vaso);
        $this->setStock($stock);
    }
    
    public function EsIgual($Helado){
        $retorno = false;
        if(!strcasecmp($this->_tipo, $Helado->_tipo) && !strcasecmp($this->_sabor, $Helado->_sabor)){
            $retorno = true;
        }
        return $retorno;
    }

    public function GuardarImagenHelado(){
        $retorno = false;
        $path_1 = 'ImagenesDeHelados';
        $path_2 = $path_1 . '/2024';

        // Comprobar si la carpeta de destino existe, si no, crearla
        if (!file_exists($path_1)) {
            mkdir($path_1);
        }
        // Comprobar si la carpeta de destino existe, si no, crearla
        if (!file_exists($path_2)) {
            mkdir($path_2);
        }
            $archivo = $this->_sabor . '_' . $this->_tipo;
            $tmpName = $_FILES["imagen"]["tmp_name"];
            $destino = $path_2 . "/" . $archivo . ".jpg";        

        if (move_uploaded_file($tmpName, $destino)) {
            echo "Se pudo guardar la imagen del Helado correctamente.\n";
            $retorno = true;
        }else{
            echo "Hubo algun problema al guardar la imagen de la venta.\n";
        }
        return $retorno;
    }
}