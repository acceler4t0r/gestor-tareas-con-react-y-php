<?php
    use Vendor\JWT;

    class AuthMiddleware{
        public function handle()
        {
            $headers = getallheaders();
            $auth = $headers['Authorization'] ?? '';

            if (!str_starts_with($auth, 'Bearer ')) {
                http_response_code(401);
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $token = str_replace('Bearer ', '', $auth);

            try {
                $payload = JWT::decode($token, $_ENV['JWT_SECRET']);
                $_REQUEST['auth'] = $payload;
            } catch (\Exception $e) {
                http_response_code(401);
                echo json_encode(['error' => $e->getMessage()]);
                exit;
            }
        }
    }

?>