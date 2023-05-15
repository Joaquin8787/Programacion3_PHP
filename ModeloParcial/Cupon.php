<?php
include_once "ManejoArchivos.php";

class Cupon
{
    public int $_id;
    public int $_idDevolucion;
    public string $_causa;
    public int $_porcentajeDescuento;
    public string $_estado;

    public function __construct(int $devolucionId, string $causa)
    {
        $this->_id = Funciones::GenerarNuevoValor(LeerDatosJSON('cupones.json'),1000,99999,"_id");
        $this->_idDevolucion = $devolucionId;
        $this->_causa = empty(trim($causa)) ? 'Distintos sabores' : $causa;
        $this->_porcentajeDescuento = 10;
        $this->_estado = "no usado";
    }

    public static function BuscarCuponActivo(array $cuponesExistentes, int $idCupon)
    {
        for ($i = 0; $i < count($cuponesExistentes); $i++) {
            if ($idCupon == $cuponesExistentes[$i]->_id &&
                !strcasecmp($cuponesExistentes[$i]->_estado, "no usado")) {
                return $i;
            }
        }
        return -1;
    }
}