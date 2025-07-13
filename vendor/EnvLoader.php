<?php

    namespace Vendor;

    use RuntimeException;

    class EnvLoader{
        protected string $envPath;

        public function __construct(string $envPath)
        {
            $this->envPath = $envPath;
        }

        public function load(){
            if (!file_exists($this->envPath)) {
                throw new RuntimeException("Archivo no encontrado: {$this->envPath}");
            }

            $lines = file($this->envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                $line = trim($line);

                if (strlen($line) === 0 || str_starts_with($line, '#')) {
                    continue;
                }

                if (strpos($line, '=') === false) {
                    continue;
                }

                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                if (!array_key_exists($name, $_ENV)) {
                    $_ENV[$name] = $value;
                    putenv("$name=$value");
                }
            }
        }
    }
