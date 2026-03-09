<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Mentahan Dashboard Tailwind
Route::get('/mentahan-dashboard', function () {
    return view('mentahan');
});

// POS
Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::post('/pos/sale', [PosController::class, 'store'])->name('pos.store');

// Inventory
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::patch('/inventory/{inventory}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');

// Products — resourceful
Route::resource('products', ProductController::class)->except(['show']);

// Reports
Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
