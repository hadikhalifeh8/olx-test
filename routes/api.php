<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/create-task', [TaskController::class, 'createTask']);
    Route::post('/update-task/{id}', [TaskController::class, 'updateTask']);
    Route::get('/get-task/{user_id}', [TaskController::class, 'gettask']);
    Route::post('/delete-task/{id}', [TaskController::class, 'deleteTask']);

    Route::post('/tasks/{id}/comments', [TaskController::class, 'addComment']);

});

