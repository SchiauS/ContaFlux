<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\AiSessionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialAccountController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');

Route::resource('companies', CompanyController::class);
Route::resource('accounts', FinancialAccountController::class);
Route::resource('transactions', FinancialTransactionController::class);
Route::resource('tasks', TaskController::class);
Route::resource('ai-sessions', AiSessionController::class)->only(['index', 'show', 'destroy']);

Route::post('/ai/chat', [AiController::class, 'chat'])->name('ai.chat');
Route::post('/ai/analyze', [AiController::class, 'analyze'])->name('ai.analyze');
Route::post('/ai/summary', [AiController::class, 'summary'])->name('ai.summary');
