<?php
    namespace App\Model;

    use Vendor\DB;

    class UsuariosModel extends DB{
        protected $tabla = "usuarios";
        protected $id = "id";
        protected $campos = [
            'correo',
            'contrasena'
        ]; 
    }

?>