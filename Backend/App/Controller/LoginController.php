<?php
    namespace App\Controller;

    use App\Model\UsuariosModel;
    use Vendor\JWT;

    class LoginController{

        private $usuarios;

        public function __construct(){
            $this->usuarios = new UsuariosModel();
        }

        public function login() {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['correo']) || empty($data['contrasena'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Correo y contrasena son requeridos']);
                exit;
            }

            $correo = $data['correo'];
            $contrasena = $data['contrasena'];

            $user = $this->usuarios->buscar('correo',$correo);
            
            if (!$user || !password_verify($contrasena, $user[0]['contrasena'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Credenciales invalidas']);
                exit;
            }

            $payload = [
                'sub' => $user[0]['id'],
                'correo' => $user[0]['correo'],
                'iat' => time(),
                'exp' => time() + 3600*24,
            ];

            $token = JWT::encode($payload, $_ENV['JWT_SECRET']);

            echo json_encode(['token' => $token]);
        }
    }


?>