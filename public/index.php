<?php
    require_once __DIR__ . '/../Vendor/AutoLoad.php';

    use Vendor\EnvLoader;
    use Vendor\Route;

    $env = new EnvLoader(__DIR__ . '/../.env');
    $env->load();

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    #preflight (cuando React hace una peticion OPTIONS), segun entendi es como una previa que hace el navegador para saber si puede solicitar algo
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    require_once __DIR__ . '/../Routes/Api.php';

    Route::handleRequest();
