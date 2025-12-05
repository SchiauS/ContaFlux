<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\AiSessionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialAccountController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');

    Route::resource('companies', CompanyController::class)->only(['index', 'update']);
    Route::resource('accounts', FinancialAccountController::class);
    Route::resource('transactions', FinancialTransactionController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('ai-sessions', AiSessionController::class)->only(['index', 'show', 'destroy']);

    Route::post('/ai/chat', [AiController::class, 'chat'])->name('ai.chat');
    Route::post('/ai/analyze', [AiController::class, 'analyze'])->name('ai.analyze');
    Route::post('/ai/summary', [AiController::class, 'summary'])->name('ai.summary');
});
