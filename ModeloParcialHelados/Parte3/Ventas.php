<?php
include_once "./ManejoArchivos.php";

class Venta{
    public int $_id;
    public int $_numeroPedido;
    public string $_emailUsuario;
    public string $_saborHelado;
    public string $_tipoHelado;
    public string $_vasoHelado;
    public string $_stockHelado;
    public string $_fechaPedido;

    #region Setter
    public function setID(){
        $this->_id = count(LeerJSON("Ventas.json"))+1;
    }
    public function setNumeroPedido(){
        $this->_numeroPedido = Funciones::GenerarNuevoValor(LeerJSON('Ventas.json'),1000,10000,"_numeroPedido");
    }
    public function setEmail(string $email){
        $auxEmail = strtolower($email);
        $this->_emailUsuario = Venta::validarEmail($auxEmail) ? $auxEmail : 'invalid_email';
    }
    public function setSabor(string $sabor){
        $this->_saborHelado = strtolower($sabor);
    }
    
    public function setTipo(string $tipo){
        $tipoLower = strtolower($tipo);
        
        if($tipoLower == "agua" || $tipoLower == "crema"){
            $this->_tipoHelado = $tipoLower;
        }else{
            random_int(0,1) == 0 ? $this->_tipoHelado = "agua" : $this->_tipoHelado = "crema";
        }
    }
    public function setVaso(string $vaso){
        $vasoLower = empty(trim($vaso)) ? "_" : strtolower($vaso);
        if($vasoLower == "cucurucho" || $vasoLower == "plastico"){
            $this->_vasoHelado = $vasoLower;
        }else{
            random_int(0,1) == 0 ? $this->_vasoHelado = "cucurucho" : $this->_vasoHelado = "plastico";
        }
    }

    public function setStock(int $stock){
        $this->_stockHelado = $stock <= 0 ? 1 : $stock;
    }

    public function setFecha(DateTime $fecha)
    {
        $auxFecha = $fecha <= new DateTime('now') ? $fecha : new DateTime('now');
        $this->_fechaPedido = $auxFecha->format('d-m-Y');
    }
    #endregion

    public function __construct($emailUsuario = "", $saborHelado = 'Chocolate', $tipoHelado = "", $vasoHelado = "",$stockHelado = 0,$fechaPedido = new DateTime('now')){
        $this->setID();
        $this->setNumeroPedido();
        $this->setEmail($emailUsuario);
        $this->setSabor($saborHelado);
        $this->setTipo($tipoHelado);
        $this->setVaso($vasoHelado);
        $this->setStock($stockHelado);
        $this->setFecha($fechaPedido);
    }

    function validarEmail(string $email) {
        $retorno =  false;
        // Expresión regular para validar email
        $patronEmail = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        
        // Comprobamos si el email cumple con el patrón definido
        if(preg_match($patronEmail, $email)){
            $retorno = true;
        }
        return $retorno;
    }
    
    public function EsIgual($numeroPedido){
        $retorno = false;
        if($this->_numeroPedido == $numeroPedido){
            $retorno = true;
        }
        return $retorno;
    }
    public static function BuscarVenta(array $arrayVentas, $numeroPedido){
        $indice = 0;
        foreach($arrayVentas as $venta){
            if($venta->_numeroPedido == $numeroPedido){
                return $indice;
                break;
            }
            $indice++;
        }
        return -1;
    }
    public function GuardarImagenVenta(){

        $retorno = false;
        $path_1 = 'ImagenesDeLaVenta';
        $path_2 = $path_1 . "/2024";

        // Comprobar si la carpeta de destino existe, si no, crearla
        if (!file_exists($path_1)) {
            mkdir($path_1);
        }
        if (!file_exists($path_2)) {
            mkdir($path_2);
        }
        //Uso el explode para separar el mail
        $emailSeparado = explode("@", $this->_emailUsuario); 
        //Guardo con tipo, sabor, vaso, mail y fecha      
        $archivo = $this->_saborHelado . '_' . $this->_tipoHelado . '_' . $this->_vasoHelado . '_' . $emailSeparado[0] . '_' . $this->_fechaPedido;
        //Obtengo la ubicacion temporal donde se subio el archivo
        $tmpName = $_FILES["imagen"]["tmp_name"];
        $destino = $path_2 . "/" . $archivo . ".jpg";
        
        if (move_uploaded_file($tmpName, $destino)) {
            echo "Se pudo guardar la imagen de la venta correctamente.\n";
            $retorno = true;
        }else{
            echo "Hubo algun problema al guardar la imagen de la venta.\n";
        }
        return $retorno;
    }
}
?>