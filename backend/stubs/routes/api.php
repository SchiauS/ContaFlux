<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;

Route::post('/ai/chat', [AiController::class, 'chat']);
Route::get('/ai/summary', [AiController::class, 'summary']);
Route::post('/ai/analyze', [AiController::class, 'analyze']);

// TODO: adaugă rute pentru upload documente, taskuri și KPI snapshots.
