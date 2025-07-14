<?php
    
    namespace App\Model;

    use Vendor\DB;

    class TareaModel extends DB{
        protected $tabla = "tareas";
        protected $id = "id";
        protected $campos = [
            "titulo",
            "descripcion",
            "estado",
            "fecha_creacion",
            "usuario_id",
            "ciudad",
            "clima_actual",
            "frase_motivacional"
        ];
    }

?>