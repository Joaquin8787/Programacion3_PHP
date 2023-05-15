<?php
include_once "./ManejoArchivos.php";

class Pizza{
    public int $_id;
    public string $_sabor;
    public float $_precio;
    public string $_tipo;
    public int $_cantidad;

    //SETTERS
    public function setID(){
        //id autoincremental
        $this->_id = count(LeerJSON("Pizza.json"))+1;
    }

    public function setSabor(string $sabor){
        $s = str_replace(' ', '_', $sabor);
        $this->_sabor = empty(trim($s)) ? "napo" : strtolower($s);
    }

    public function setPrecio(float $precio){
        $precio <= 0 ? $this->_precio = 175 : $this->_precio = $precio;
    }

    public function setTipo(string $tipo){
        $tipoLower = empty(trim($tipo)) ? "_" : strtolower($tipo);
        if($tipoLower == "molde" || $tipoLower == "piedra"){
            $this->_tipo = $tipoLower;
        }else{
            random_int(0,1) == 0 ? $this->_tipo = "molde" : $this->_tipo = "piedra";
        }
    }

    public function setCantidad(int $cantidad){
        $cantidad <= 0 ? $this->_cantidad = 1 : $this->_cantidad = $cantidad;
    }

    //CONSTRUCTOR
    public function __construct(string $sabor, string $tipo, float $precio = 0, int $cantidad = 0){
        $this->setID();
        $this->setSabor($sabor);
        $this->setPrecio($precio);
        $this->setTipo($tipo);
        $this->setCantidad($cantidad);
    }
    
    public function EsIgual($pizza){
        $retorno = false;
        if(!strcasecmp($this->_tipo, $pizza->_tipo) && !strcasecmp($this->_sabor, $pizza->_sabor)){
            $retorno = true;
        }
        return $retorno;
    }
    public function GuardarImagenPizza(){
        $retorno = false;
        $path = 'ImagenesDePizzas';
        // Comprobar si la carpeta de destino existe, si no, crearla
        if (!file_exists($path)) {
            mkdir($path);
        }
        $archivo = $this->_sabor . '_' . $this->_tipo;
        $tmpName = $_FILES["imagen"]["tmp_name"];
        $destino = $path . "/" . $archivo . ".jpg";

        if (move_uploaded_file($tmpName, $destino)) {
            echo "Se pudo guardar la imagen de la pizza correctamente.\n";
            $retorno = true;
        }else{
            echo "Hubo algun problema al guardar la imagen de la venta.\n";
        }
        return $retorno;
    }
}