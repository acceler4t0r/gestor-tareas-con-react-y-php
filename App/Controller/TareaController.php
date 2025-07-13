<?php
    namespace App\Controller;

    use App\Model\TareaModel;

    class TareaController {
        private $tareas;

        public function __construct() {
            $this->tareas = new TareaModel();
        }

        public function index() {
            $userId = $this->getUserIdFromToken();

            $tareas = $this->tareas->buscar('usuario_id',$userId);

            echo json_encode($tareas);
        }

        public function show($id) {
            $tarea = $this->tareas->buscar('id', $id);

            if (!$tarea) {
                http_response_code(404);
                echo json_encode(['error' => 'Tarea no encontrada']);
                exit;
            }

            echo json_encode($tarea);
        }

        public function create() {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['titulo'])) {
                http_response_code(400);
                echo json_encode(['error' => 'El título es obligatorio']);
                exit;
            }

            $userId = $this->getUserIdFromToken();

            $nuevaTarea = [
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'] ?? '',
                'estado' => $data['estado'] ?? 'pendiente',
                'usuario_id' => $userId,
                'ciudad' => $data['ciudad'] ?? null,
                'clima_actual' => $data['clima_actual'] ?? null,
                'frase_motivacional' => $data['frase_motivacional'] ?? null,
            ];

            $this->tareas->insert($nuevaTarea);
            $inserted = $this->tareas->get();

            if ($inserted) {
                http_response_code(201);
                echo json_encode(['message' => 'Tarea creada']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al crear tarea']);
            }
        }

        public function update(int $id) {
            $data = json_decode(file_get_contents('php://input'), true);

            $tareaExistente = $this->tareas->buscar('id', $id);

            if (!$tareaExistente) {
                http_response_code(404);
                echo json_encode(['error' => 'Tarea no encontrada']);
                exit;
            }

            $actualizarTarea = [];

            if (isset($data['titulo'])) $actualizarTarea['titulo'] = $data['titulo'];
            if (isset($data['descripcion'])) $actualizarTarea['descripcion'] = $data['descripcion'];
            if (isset($data['estado'])) $actualizarTarea['estado'] = $data['estado'];
            if (isset($data['ciudad'])) $actualizarTarea['ciudad'] = $data['ciudad'];
            if (isset($data['clima_actual'])) $actualizarTarea['clima_actual'] = $data['clima_actual'];
            if (isset($data['frase_motivacional'])) $actualizarTarea['frase_motivacional'] = $data['frase_motivacional'];

            $this->tareas->update($actualizarTarea, $id);
            $updated = $this->tareas->get();

            if ($updated) {
                echo json_encode(['message' => 'Tarea actualizada']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al actualizar tarea']);
            }
        }

        public function delete($id) {
            $tareaExistente = $this->tareas->buscar('id', $id);

            if (!$tareaExistente) {
                http_response_code(404);
                echo json_encode(['error' => 'Tarea no encontrada']);
                exit;
            }

            $this->tareas->delete($id);
            $deleted = $this->tareas->get();

            if ($deleted) {
                echo json_encode(['message' => 'Tarea eliminada']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al eliminar tarea']);
            }
        }

        private function getUserIdFromToken() {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? '';

            if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
                http_response_code(401);
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $token = substr($authHeader, 7);

            try {
                $payload = \Vendor\JWT::decode($token, $_ENV['JWT_SECRET']);
                return $payload['sub'] ?? null;
            } catch (\Exception $e) {
                http_response_code(401);
                echo json_encode(['error' => $e->getMessage()]);
                exit;
            }
        }
    }
?>
