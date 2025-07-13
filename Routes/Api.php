<?php

    use App\Controller\LoginController;
    use App\Controller\TareaController;
    use Vendor\Route;

    Route::post('/login', [LoginController::class, 'login']);

    // Rutas protegidas con middleware (requieren token JWT)
    Route::get('/tareas', [TareaController::class, 'index'], [AuthMiddleware::class]);
    Route::get('/tareas/{id}', [TareaController::class, 'show'], [AuthMiddleware::class]);
    Route::post('/tareas', [TareaController::class, 'create'], [AuthMiddleware::class]);
    Route::put('/tareas/{id}', [TareaController::class, 'update'], [AuthMiddleware::class]);
    Route::delete('/tareas/{id}', [TareaController::class, 'delete'], [AuthMiddleware::class]);
    
?>