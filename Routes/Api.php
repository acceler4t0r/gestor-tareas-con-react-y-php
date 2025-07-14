<?php

    use App\Controller\LoginController;
    use App\Controller\TareaController;
use App\Controller\UsuarioController;
use Vendor\Route;

    Route::post('/login', [LoginController::class, 'login']);

    // Rutas protegidas con middleware (requieren token JWT)
    Route::get('/tareas', [TareaController::class, 'index'], [AuthMiddleware::class]);
    Route::post('/tareas', [TareaController::class, 'create'], [AuthMiddleware::class]);
    Route::put('/tareas/{id}', [TareaController::class, 'update'], [AuthMiddleware::class]);
    Route::delete('/tareas/{id}', [TareaController::class, 'delete'], [AuthMiddleware::class]);
    Route::post('/usuario', [UsuarioController::class, 'create']);

    //Para realizar prueba del README
    Route::get('/test', function(){
        echo json_encode(['message' => 'Test exitoso']);
    });
    
?>