<?php

use App\Http\Controllers\MonitorController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Public monitor screen.
Route::get('/', [MonitorController::class, 'index'])->name('monitor');
Route::get('/monitor', [MonitorController::class, 'index']);
Route::get('/monitor/data', [MonitorController::class, 'data'])->name('monitor.data');

// Project management is temporarily public while authentication is disabled.
Route::get('/dashboard', fn () => redirect()->route('admin.projects.index'))->name('dashboard');
Route::get('/admin/projects', [ProjectController::class, 'index'])->name('admin.projects.index');
Route::post('/admin/projects', [ProjectController::class, 'store'])->name('admin.projects.store');
Route::put('/admin/projects/{project}', [ProjectController::class, 'update'])->name('admin.projects.update');
Route::delete('/admin/projects/{project}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');
