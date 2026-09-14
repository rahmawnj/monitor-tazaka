<?php

use App\Http\Controllers\MonitorController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MonitorController::class, 'index'])->name('monitor');
Route::get('/monitor', [MonitorController::class, 'index']);
Route::get('/monitor/data', [MonitorController::class, 'data'])->name('monitor.data');

Route::get('/dashboard', fn () => redirect()->route('admin.projects.index'))->name('dashboard');
Route::get('/admin/projects', [ProjectController::class, 'index'])->name('admin.projects.index');
Route::get('/admin/projects/create', [ProjectController::class, 'create'])->name('admin.projects.create');
Route::post('/admin/projects', [ProjectController::class, 'store'])->name('admin.projects.store');
Route::get('/admin/projects/{project}', [ProjectController::class, 'show'])->name('admin.projects.show');
Route::get('/admin/projects/{project}/edit', [ProjectController::class, 'edit'])->name('admin.projects.edit');
Route::put('/admin/projects/{project}', [ProjectController::class, 'update'])->name('admin.projects.update');
Route::patch('/admin/projects/{project}/progress', [ProjectController::class, 'updateProgress'])->name('admin.projects.progress');
Route::post('/admin/projects/reorder', [ProjectController::class, 'reorder'])->name('admin.projects.reorder');
Route::delete('/admin/projects/{project}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');
