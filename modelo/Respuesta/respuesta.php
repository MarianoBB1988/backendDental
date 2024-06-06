<?php  
class Respuesta{
    public $success;
    public $mensaje;
    public $data;

    function __construct($success, $mensaje, $data){
        $this->success = $success;
        $this->mensaje = $mensaje;
        $this->data = $data;
    }
}

?>