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
    /*
    En PHP, __construct es un método mágico que se llama 
    automáticamente cuando se crea una instancia de una clase.
     Este método se utiliza para inicializar las propiedades 
     de un objeto o realizar tareas necesarias antes de que el
      objeto esté listo para su uso.
Cuando defines un método __construct dentro de una clase, 
este método se ejecutará automáticamente cada vez que creas 
un nuevo objeto de esa clase utilizando el operador new.
*/
}

?>