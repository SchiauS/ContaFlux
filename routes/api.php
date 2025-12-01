<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\AiSessionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialAccountController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => ['status' => 'ok']);

Route::apiResource('companies', CompanyController::class);
Route::apiResource('accounts', FinancialAccountController::class);
Route::apiResource('transactions', FinancialTransactionController::class);
Route::apiResource('tasks', TaskController::class);
Route::apiResource('ai-sessions', AiSessionController::class)->only(['index', 'show', 'destroy']);

Route::post('/ai/chat', [AiController::class, 'chat']);
Route::post('/ai/analyze', [AiController::class, 'analyze']);
Route::post('/ai/summary', [AiController::class, 'summary']);

Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
