<?php
/*
AltaVenta.php: (por POST)se recibe el email del usuario y el sabor,tipo y cantidad ,si el ítem existe en
Pizza.json, y hay stock guardar en la base de datos( con la fecha, número de pedido y id autoincremental ) y se
debe descontar la cantidad vendida del stock .
*/
include_once "./ManejoArchivos.php";

class Venta{
    public int $_id;
    public int $_numeroPedido;
    public string $_emailUsuario;
    public string $_saborPizza;
    public string $_tipoPizza;
    public int $_cantidadPizza;
    public string $_fechaPedido;

    #region Setter
    public function setID(){
        $this->_id = count(LeerJSON("Ventas.json"))+1;
    }
    public function setNumeroPedido(){
        $this->_numeroPedido = Venta::GenerarNumeroPedido(LeerJSON("Ventas.json"));
    }
    public function setEmail(string $email){
        $auxEmail = strtolower($email);
        $this->_emailUsuario = Venta::validarEmail($auxEmail) ? $auxEmail : 'invalid_email';
    }
    public function setSabor(string $sabor){
        $this->_saborPizza = strtolower($sabor);
    }
    public function setTipo(string $tipo){
        $tipoLower = strtolower($tipo);
        
        if($tipoLower == "molde" || $tipoLower == "piedra"){
            $this->_tipoPizza = $tipoLower;
        }else{
            random_int(0,1) == 0 ? $this->_tipo = "molde" : $this->_tipo = "piedra";
        }
    }
    public function setCantidad(int $cantidad){
        $this->_cantidadPizza = $cantidad <= 0 ? 1 : $cantidad;
    }
    public function setFecha(string $strFecha)
    {
        $fecha = DateTime::createFromFormat('d-m-Y', $strFecha) ?: new DateTime('now');
        $auxFecha = $fecha <= new DateTime('now') ? $fecha : new DateTime('now');

        $this->_fechaPedido = $auxFecha->format('d-m-Y');
    }
    #endregion

    public function __construct($emailUsuario = 'asd@asss.com', $saborPizza = 'muzza', $tipoPizza, $cantidadPizza,$fechaPedido = new DateTime('now')){
        $this->setID();
        $this->setNumeroPedido();
        $this->setEmail($emailUsuario);
        $this->setSabor($saborPizza);
        $this->setTipo($tipoPizza);
        $this->setCantidad($cantidadPizza);
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
    
    public static function GenerarNumeroPedido(array $arrayVentas){
        $numeroPedido = random_int(1000, 100000);

        while (array_search($numeroPedido, array_column($arrayVentas, '_numeroPedido')) != false) {
            $numeroPedido = rand(1000, 100000);
        }
        return $numeroPedido;
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
        return $retorno;
    }
    public function GuardarImagenVenta(){

        $retorno = false;

        //Verifico si la carpeta existe 
        is_dir(getcwd() . '/ImagenesDeLaVenta') ? : mkdir(getcwd() . '/ImagenesDeLaVenta');
        
        //Uso el explode para separar el mail
        $emailSeparado = explode("@", $this->_emailUsuario); 

        //Guardo con tipo, sabor, mail y fecha      
        $archivo = $this->_tipoPizza . '_' . $this->_saborPizza . '_' . $emailSeparado[0] . '_' . $this->_fechaPedido;
        $destino = "ImagenesDeLaVenta/" . $archivo . ".jpg";
        
        //Obtengo la ubicacion temporal donde se subio el archivo
        $tmpName = $_FILES["imagen"]["tmp_name"];
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