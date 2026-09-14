<?php

use App\Http\Controllers\MonitorController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public monitor screen.
Route::get('/', [MonitorController::class, 'index'])->name('monitor');
Route::get('/monitor', [MonitorController::class, 'index']);
Route::get('/monitor/data', [MonitorController::class, 'data'])->name('monitor.data');

// Simple PIN login for the project management area.
Route::get('/login', function (Request $request) {
    if ($request->session()->get('pin_authenticated') === true) {
        return redirect()->route('admin.projects.index');
    }

    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate(['pin' => ['required', 'string', 'max:20']]);

    $configuredPin = (string) config('monitor.pin', '');
    $submittedPin = (string) $request->input('pin');

    if ($configuredPin === '' || !hash_equals($configuredPin, $submittedPin)) {
        return back()->withErrors(['pin' => 'PIN salah.'])->withInput();
    }

    $request->session()->regenerate();
    $request->session()->put('pin_authenticated', true);

    return redirect()->intended(route('admin.projects.index'));
})->name('login.submit');

Route::post('/logout', function (Request $request) {
    $request->session()->forget('pin_authenticated');
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

// Project management requires the PIN session.
Route::middleware(function (Request $request, $next) {
    if ($request->session()->get('pin_authenticated') !== true) {
        return redirect()->route('login');
    }

    return $next($request);
})->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('admin.projects.index'))->name('dashboard');
    Route::get('/admin/projects', [ProjectController::class, 'index'])->name('admin.projects.index');
    Route::post('/admin/projects', [ProjectController::class, 'store'])->name('admin.projects.store');
    Route::put('/admin/projects/{project}', [ProjectController::class, 'update'])->name('admin.projects.update');
    Route::delete('/admin/projects/{project}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');
});
