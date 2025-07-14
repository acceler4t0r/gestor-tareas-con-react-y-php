<?php
    namespace Vendor;

    use PDO;
    use PDOException;

    abstract class DB extends Connection {

        protected $tabla;
        protected $id;
        protected $campos;
        protected $sql = "";
        protected $params = [];
        protected $conn;

        public function __construct() {
            $this->conn = $this->getConnection();
            $this->params = [];
        }

        protected function organizarCampos(array $campos): string {
            return implode(', ', $campos);
        }

        protected function organizarPlaceholders(int $cantidad): string {
            return rtrim(str_repeat('?, ', $cantidad), ', ');
        }

        public function insert(array $valores) {
            $camposStr = $this->organizarCampos($this->campos);
            $placeholders = $this->organizarPlaceholders(count($this->campos));

            $this->sql = "INSERT INTO $this->tabla ($camposStr) VALUES ($placeholders)";

            $this->params = [];
            foreach ($this->campos as $campo) {
                $this->params[] = $valores[$campo] ?? null;
            }
        }

        public function select(array $campos = ['*']) {
            $camposStr = $this->organizarCampos($campos);
            $this->sql = "SELECT $camposStr FROM $this->tabla";
            $this->params = [];
        }

        public function where(string $campo, string $operador, $valor, string $conector = 'AND') {
            $operador = strtoupper($operador);
            $hasWhere = stripos($this->sql, 'WHERE') !== false;

            $clausula = "$campo $operador ?";
            if ($hasWhere) {
                $this->sql .= " $conector $clausula";
            } else {
                $this->sql .= " WHERE $clausula";
            }

            $this->params[] = $valor;
        }

        public function join(string $tablaJoin, string $campoJoin, string $campoLocal) {
            $this->sql .= " INNER JOIN $tablaJoin ON $tablaJoin.$campoJoin = $this->tabla.$campoLocal";
        }

        public function delete($id) {
            $this->sql = "DELETE FROM $this->tabla WHERE $this->id = ?";
            $this->params = [$id];
        }

        public function update(array $valores, $id) {
            $setParts = [];
            $this->params = [];

            foreach ($valores as $campo => $valor) {
                $setParts[] = "$campo = ?";
                $this->params[] = $valor;
            }

            $setStr = implode(', ', $setParts);

            $this->sql = "UPDATE $this->tabla SET $setStr WHERE $this->id = ?";
            $this->params[] = $id;
        }

        public function get() {
            try {
                $stmt = $this->conn->prepare($this->sql);
                $stmt->execute($this->params);

                if (stripos(trim($this->sql), 'SELECT') === 0) {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                return true;
            } catch (PDOException $e) {
                error_log("DB error: " . $e->getMessage());
                return false;
            }
        }

        public function verSql() {
            return [
                'sql' => $this->sql,
                'params' => $this->params,
            ];
        }

        public function orderBy(array $campos) {
            $camposStr = $this->organizarCampos($campos);
            $this->sql .= " ORDER BY $camposStr";
        }

        public function groupBy(array $campos) {
            $camposStr = $this->organizarCampos($campos);
            $this->sql .= " GROUP BY $camposStr";
        }

        public function buscar(string $campo, $valor) {
            $this->select();
            $this->where($campo, '=', $valor);
            return $this->get();
        }

        public function beginTransaction() {
            $this->conn->beginTransaction();
        }

        public function commit() {
            $this->conn->commit();
        }

        public function rollBack() {
            $this->conn->rollBack();
        }

        public function lastInsertId() {
            return $this->conn->lastInsertId();
        }
    }
