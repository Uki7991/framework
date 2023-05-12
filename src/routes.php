<?php

use App\Http\Route;
use Src\App\Http\Controllers\API\TaskController;

Route::get('/api/tasks/([\d]+)', TaskController::class, 'show');
Route::get('/api/tasks', TaskController::class, 'index');
Route::post('/api/tasks', TaskController::class, 'store');
Route::get('/api/get-task', TaskController::class, 'showWithQuery');
