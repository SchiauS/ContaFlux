<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\AiSessionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FinancialAccountController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ReportsController;
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

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');

    Route::resource('companies', CompanyController::class)->only(['index', 'update']);
    Route::resource('accounts', FinancialAccountController::class)
        ->parameters(['accounts' => 'financialAccount']);
    Route::resource('transactions', FinancialTransactionController::class)
        ->parameters(['transactions' => 'financialTransaction']);
    Route::resource('employees', EmployeeController::class);
    Route::post('/employees/{employee}/time-entries', [EmployeeController::class, 'storeTimeEntry'])->name('employees.time-entries.store');
    Route::post('/employees/{employee}/leaves', [EmployeeController::class, 'storeLeave'])->name('employees.leaves.store');
    Route::post('/employees/{employee}/payroll', [EmployeeController::class, 'paySalary'])->name('employees.payroll');
    Route::patch('/employees/{employee}/terminate', [EmployeeController::class, 'terminate'])->name('employees.terminate');
    Route::patch('/employees/{employee}/reinstate', [EmployeeController::class, 'reinstate'])->name('employees.reinstate');
    Route::resource('tasks', TaskController::class);
    Route::resource('ai-sessions', AiSessionController::class)->only(['index', 'show', 'destroy']);

    Route::post('/ai/chat', [AiController::class, 'chat'])->name('ai.chat');
    Route::post('/ai/analyze', [AiController::class, 'analyze'])->name('ai.analyze');
    Route::post('/ai/summary', [AiController::class, 'summary'])->name('ai.summary');
});
