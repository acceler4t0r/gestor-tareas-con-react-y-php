<?php
    namespace App\Controller;

    use App\Model\UsuariosModel;

    class UsuarioController{
        private $usuarios;
        public function __construct(){
            $this->usuarios = new UsuariosModel();
        }
        public function create() {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['correo']) || empty($data['contrasena'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Todos los campos son requeridos']);
                exit;
            }

            $existe = $this->usuarios->buscar('correo', $data['correo']);
            if ($existe) {
                http_response_code(409);
                echo json_encode(['error' => 'Correo ya registrado']);
                exit;
            }

            $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_BCRYPT);
            $this->usuarios->insert($data);
            $this->usuarios->get();

            echo json_encode(['mensaje' => 'Usuario registrado']);
        }

    }
?>